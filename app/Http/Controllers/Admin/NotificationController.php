<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\AccountRepository;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Notifications\SendPushNotification;
use App\Repositories\GroupRepository;
use App\Repositories\SchoolRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends BaseController
{
    protected $schoolRepository;
    protected $groupRepository;
    protected $accountRepository;

    public function __construct(SchoolRepository $schoolRepository, GroupRepository $groupRepository, AccountRepository $accountRepository)
    {
        $this->schoolRepository = $schoolRepository;
        $this->groupRepository = $groupRepository;
        $this->accountRepository = $accountRepository;
    }
    public function index()
    {
        if (auth()->user()->role_id == Consts::ADMIN) {
            $school = $this->schoolRepository->getSchoolWithGrade();
            $group = $this->groupRepository->getAll();
        } else {
            $school = $this->accountRepository->getSchoolByMentor(auth()->user()->id);
            $group = $this->accountRepository->getGroupByMentor(auth()->user()->id);
        }
        return view('admin.notification.index', compact('school', 'group'));
    }
    public function getSchoolGroup()
    {
        $school = $this->schoolRepository->getSchoolWithGrade();
        $group = $this->groupRepository->getAll();
        return $this->sendResponse([
            "school" => $school,
            "group" => $group,
        ]);
    }
    public function updateToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token' => $request->token]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }

    public function notification(Request $request)
    {

        $school_id = $request->input('school_id', null);
        $group_id = $request->input('group_id', null);
        $grade_id = $request->input('grade_id', null);
        $grade = null;
        if (auth()->user()->role_id == Consts::MENTOR) {
            $school = $this->accountRepository->getSchoolByMentor(auth()->user()->id)->first();
            if (!empty($school)) {
                $school_id = $school->id;
            }
            $group = $this->accountRepository->getGroupByMentor(auth()->user()->id)->first();
            if (!empty($group)) {
                $group_id = $group->id;
            }
            if (empty($school) && empty($group)) {
                $request->validate([
                    'school' => 'required',
                    'title' => 'required',
                    'message' => 'required'
                ], [
                        'school.required' => __("response.notHaveSchoolGroup"),
                    ]);
            }
        }
        $request->validate([
            'title' => 'required',
            'message' => 'required'
        ]);
        try {
            if (isset($school_id) && !empty($school_id) && empty($grade_id)) {
                $listGrade = $this->schoolRepository->find($school_id)->grades;
                $grade = array_column($listGrade->toArray(), 'id');
            }
            if (empty($grade) && !empty($school_id) && empty($grade_id) ) {
                return $this->sendResponse(__("response.success"));
            }

            $Tokens = User::whereNotNull('fcm_token')
                ->when(!empty($grade), function ($query) use ($grade) {
                    return $query->whereIn('grade_id', $grade);
                })
                ->when(!empty($group_id), function ($query) use ($group_id) {
                    return $query->where('group_id', $group_id);
                })
                ->when(!empty($grade_id), function ($query) use ($grade_id) {
                    return $query->where('grade_id', $grade_id);
                })
                ->pluck('fcm_token')->toArray();
                // dd($Tokens);
            $uniquetokens = array_unique($Tokens);
            $fcmTokens = [];
            foreach($uniquetokens as $token){
                $fcmTokens[] = $token;
            }
            $count_student = count($fcmTokens);
            // Notification::send(null, new SendPushNotification($request->title, $request->message, $fcmTokens));

            /* or */

            // auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));

            /* or */

            // Larafirebase::withTitle($request->title)
            //     ->withBody($request->message)
            //     ->sendMessage($fcmTokens);
            $SERVER_API_KEY = env('FIREBASE_SERVER_KEY');
            $data = [
                "registration_ids" => $fcmTokens,
                "notification" => [
                    "title" => $request->title,
                    "body" => $request->message,
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            return $this->sendResponse(__("response.success"));
        } catch (\Exception $e) {
            report($e);
            return $this->sendError($e);
        }
    }
}
