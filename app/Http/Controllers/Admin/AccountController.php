<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AccountRequest;
use App\Imports\AccountsImport;
use App\Repositories\AccountRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ErrorValidateUser;
use App\Http\Requests\Admin\ChangePassword;
use App\Http\Requests\Admin\ExcelRequest;
use App\Repositories\GroupRepository;
use App\Repositories\SchoolRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccountController extends BaseController
{
    protected $accountRepository;
    protected $schoolRepository;
    protected $groupRepository;

    public function __construct(AccountRepository $accountRepository, SchoolRepository $schoolRepository, GroupRepository $groupRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->schoolRepository = $schoolRepository;
        $this->groupRepository = $groupRepository;
    }

    public function importData(ExcelRequest $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $request = $request->all();
            $fileExcel = $request['file_excel'];
            $extension = $fileExcel->getClientOriginalExtension();
            if (!in_array($extension, ['csv', 'xls', 'xlsx'])) {
                $request->validate([
                    'excel' => 'required',
                ],
                [
                    "excel.required" => __('response.Invalid_file_excel'),
                ]);
            }
            $file = '/public/excel/Error_' . $fileExcel->getClientOriginalName();
            try {
                $accountImport = new AccountsImport();
                Excel::import($accountImport, $fileExcel);
                return $this->sendResponse(__("response.success"));
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                Excel::store(new ErrorValidateUser($failures), $file);
                return $this->sendError(asset('storage/excel/Error_' . $fileExcel->getClientOriginalName()));
            }
        }
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit', 20);
        if (Auth::user()->role_id == Consts::ADMIN) {
            $user = $this->accountRepository->getAllUser(20);
            $listSchool = $this->schoolRepository->getAll();
            $listGroup = $this->groupRepository->getAll();
        } elseif (Auth::user()->role_id == Consts::MENTOR) {
            $id = Auth::user()->id;
            $user = $this->accountRepository->getUserByMentor($id);
            $page = $request->input('page', 1);
            $offset = ($page * $limit) - $limit;
            $totalUsers = count($user);
            $UsersForCurrentPage = array_slice($user->all(), $offset, $limit, true);
            $user = new LengthAwarePaginator($UsersForCurrentPage, $totalUsers, $limit, $page, ['path' => Paginator::resolveCurrentPath()]);
            $listSchool = $this->accountRepository->getSchoolByMentor($id);
            $listGroup = $this->accountRepository->getGroupByMentor($id);
        }
        return view("admin.users.index", compact(
            "user",
            "listSchool",
            "listGroup"
        )
        );
    }
    public function showPassword($id)
    {
        if (auth()->user()->role_id == Consts::ADMIN || $this->accountRepository->checkMentorUser(auth()->user()->id, $id)) {
            $data = $this->accountRepository->find($id);
            // $password = substr($data->display_password, 15);
            // $password = Crypt::decrypt($password);
            return $this->sendResponse([
                "password" => $data->display_password
            ]);
        }
    }
    public function changeStatus($id)
    {
        if (auth()->user()->role_id == Consts::ADMIN || $this->accountRepository->checkMentorUser(auth()->user()->id, $id)) {
            $this->accountRepository->changeStatus($id);
        }
    }
    public function search(Request $request)
    {
        $school_id = $request->input('school_id', null);
        $group_id = $request->input('group_id', null);
        $school_year_id = $request->input('school_year_id', null);
        $grade_id = $request->input('grade_id', null);
        $searchKey = $request->input('searchKey', null);
        $md = Carbon::now()->format('m-d');
        if ($md >= Consts::TIME_START_YEAR) {
            $schoolYear = $request->input('schoolYear', Carbon::now()->year);
        } elseif ($md <= Consts::TIME_END_YEAR) {
            $schoolYear = $request->input('schoolYear', Carbon::now()->subYear()->year);
        }
        if (Auth::user()->role_id == Consts::MENTOR && empty($school_id) && empty($group_id) && empty($grade_id)) {
            $limit = 20;
            $user = $this->accountRepository->getUserByMentor(Auth::user()->id, $searchKey, $schoolYear);
            $page = $request->input('page', 1);
            $offset = ($page * $limit) - $limit;
            $totalUsers = count($user);
            $UsersForCurrentPage = array_slice($user->all(), $offset, $limit, true);
            $user = new LengthAwarePaginator($UsersForCurrentPage, $totalUsers, $limit, $page, ['path' => Paginator::resolveCurrentPath()]);
        } else {
            $user = $this->accountRepository->searchAllUser($school_year_id, $school_id, $grade_id, $searchKey, $group_id, $schoolYear);
        }
        return view("admin.users.grid", compact(
            "user"
        )
        );
    }
    public function render_data(Request $request)
    {
        if ($request->perPage) {
            $perPage = $request->perPage;
        } else
            $perPage = 20;
        $user = $this->accountRepository->getAllUser($perPage);
        return view("admin.users.grid", compact(
            "user"
        )
        );
    }
    public function create()
    {
        $school = $this->schoolRepository->getAll();
        $group = $this->groupRepository->getAll();
        return view('admin.users.add', compact(
            "school",
            "group"
        )
        );
    }
    public function store(AccountRequest $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $data = $request->except("role_id", "school_id");
            if ($request->school_id) {
                $school = $this->schoolRepository->find($request->school_id);
                $data['student_code'] = $school->code . $request->student_id;
                $request['student_id'] = $school->code . $request->student_id;
            } elseif ($request->group_id) {
                $group = $this->groupRepository->find($request->group_id);
                $data['student_code'] = $group->code . $request->student_id;
                $request['student_id'] = $group->code . $request->student_id;
            }
            $request->validate(
                [
                    'student_id' => 'unique:accounts,student_code,NULL,id,deleted_at,NULL',
                ],
                [
                    "student_id.unique" => __('response.student_id_unique'),
                ]
            );
            if (isset($request->school_id)) {
                $request->validate([
                    'grade_id' => 'required',
                ]);
            }
            if (empty($request->school_id) && empty($request->group_id)) {
                $request->validate([
                    'school_group' => 'required',
                ]);
            }
            if (!empty($request->birthday)) {
                $data['birthday'] = Carbon::createFromFormat('d/m/Y', $data['birthday'])->format('Y-m-d');
            }
            $data['expired_at'] = Carbon::createFromFormat('d/m/Y', $data['expired_at'])->format('Y-m-d 23:59:59');
            try {
                $data['password'] = $request->input('password', Consts::PASSWORD_DEFAULT);
                $encrypted = Crypt::encrypt($data['password']);
                $password_hash = Str::random(15) . $encrypted;
                $data['display_password'] = $password_hash;
                $data['role_id'] = 1;

                $this->accountRepository->create($data);
                return $this->sendResponse([
                    "url" => url("/admin/user")
                ]);
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }
    }
    public function show($id)
    {
        $data = $this->accountRepository->find($id);
        return $this->sendResponse([
            "user" => $data
        ]);
    }
    public function edit($id)
    {
        if (auth()->user()->role_id == Consts::ADMIN || $this->accountRepository->checkMentorUser(auth()->user()->id, $id)) {
            $user = $this->accountRepository->getUserEdit($id);
            $school = $this->schoolRepository->getAll();
            $group = $this->groupRepository->getAll();
            return view('admin.users.edit', compact(
                "user",
                "school",
                "group"
            )
            );
        }
    }
    public function update(AccountRequest $request, $id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            try {
                $data = $request->only('full_name', 'birthday', 'expired_at');
                // $data = $request->except("role_id");
                // $user = $this->accountRepository->find($id);
                // if($user->grade_id != null && $data['group_id'] != null){
                //     $data['group_id'] = null;
                // }
                // if($user->group_id != null && $data['grade_id'] != null){
                //     $data['group_id'] = null;
                // }
                if (!empty($request->birthday)) {
                    $data['birthday'] = Carbon::createFromFormat('d/m/Y', $data['birthday'])->format('Y-m-d');
                }
                $data['expired_at'] = Carbon::createFromFormat('d/m/Y', $data['expired_at'])->format('Y-m-d 23:59:59');
                try {
                    DB::beginTransaction();
                    $this->accountRepository->update($data, $id);
                    DB::commit();
                    return $this->sendResponse([
                        "url" => url("/admin/user")
                    ]);
                } catch (Exception $e) {
                    DB::rollback();
                    return $this->sendError($e->getMessage());
                }
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            try {
                $this->accountRepository->destroy($id);
                return $this->sendResponse(__('response.delete'));
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }
    }
    public function ChangePassword(ChangePassword $request)
    {
        if (Hash::check($request->current_password, Auth::user()->password)) {
            try {
                DB::beginTransaction();
                $this->accountRepository->ChangePassword($request->password);
                DB::commit();
                return $this->sendResponse(__('auth.password_change'));
            } catch (Exception $e) {
                DB::rollback();
                return $this->sendError(__('auth.change_error'));
            }
        } else {
            return $this->sendError(__('auth.old_password_incorrect'));
        }
    }
    public function ChangeStudentPassword(ChangePassword $request, $id)
    {
        if (auth()->user()->role_id == Consts::ADMIN || $this->accountRepository->checkMentorUser(auth()->user()->id, $id)) {
            $user = $this->accountRepository->find($id);
            if (Hash::check($request->current_password, $user->password)) {
                try {
                    DB::beginTransaction();
                    $this->accountRepository->ChangeUserPassword($request->password, $id);
                    DB::commit();
                    return $this->sendResponse(__('auth.password_change'));
                } catch (Exception $e) {
                    DB::rollback();
                    return $this->sendError(__('auth.change_error'));
                }
            } else {
                return $this->sendError(__('auth.old_password_incorrect'));
            }
        }
    }
}
