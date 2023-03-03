<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SchoolRequest;
use App\Repositories\AccountRepository;
use App\Repositories\GradeRepository;
use App\Repositories\MentorRepository;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\SchoolRepository;
use Illuminate\Support\Facades\Auth;

class SchoolController extends BaseController
{
    protected $schoolRepository;
    protected $accountRepository;
    protected $gradeRepository;
    protected $mentorRepository;

    public function __construct(
        SchoolRepository $schoolRepository,
        GradeRepository $gradeRepository,
        AccountRepository $accountRepository,
        MentorRepository $mentorRepository
    ) {
        $this->schoolRepository = $schoolRepository;
        $this->accountRepository = $accountRepository;
        $this->gradeRepository = $gradeRepository;
        $this->mentorRepository = $mentorRepository;
    }
    public function index()
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $school = $this->schoolRepository->listSchool(20);
            return view("admin.school.index", compact("school"));
        }
    }
    public function list()
    {
        $school = $this->schoolRepository->getAll();
        return $this->sendResponse([
            "school" => $school
        ]);
    }
    public function listBySchoolYear($id)
    {
        $school = $this->schoolRepository->listBySchoolYear($id);
        return $this->sendResponse([
            "school" => $school
        ]);
    }
    public function searchList(Request $request)
    {
        $searchKey = $request->searchKey;
        $school = $this->schoolRepository->searchList($searchKey);
        return $this->sendResponse([
            "school" => $school
        ]);
    }
    public function search(Request $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            if ($request->perPage) {
                $perPage = $request->perPage;
            } else $perPage = 20;
            $searchKey = $request->searchKey;
            $school = $this->schoolRepository->search($searchKey, $perPage);
            return view('admin.school.grid', compact('school'))->render();
        }
    }
    public function render_data(Request $request)
    {
        $school = $this->schoolRepository->listSchool(20);
        return view('admin.school.grid', compact('school'))->render();
    }
    public function create()
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $admin = $this->accountRepository->getMentor();
            return view('admin.school.add', compact("admin"));
        }
    }
    public function store(SchoolRequest $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $data = $request->all();
            if (Auth::user()->role_id == Consts::STUDENT) {
                return $this->sendError(__('response.error'));
            }
            try {
                DB::beginTransaction();
                $school = $this->schoolRepository->create($data);
                $mentor['school_id'] = $school->id;
                if (isset($request->admin)) {
                    foreach ($request->admin as $admin) {
                        $mentor['account_id'] = $admin;
                        $this->mentorRepository->create($mentor);
                    }
                }
                $grade['school_id'] = $school->id;
                if (isset($request->grade)) {
                    foreach ($request->grade as $grades) {
                        $grade['name'] = $grades['name'];
                        $grade['code'] = $grades['code'];
                        $this->gradeRepository->create($grade);
                    }
                }
                DB::commit();
                return $this->sendResponse([
                    "school" => $data,
                    "url" => url("/admin/school")
                ]);
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->sendError($exception->getMessage());
            }
        }
    }
    public function show($id)
    {
        $data = $this->schoolRepository->find($id);
        return $this->sendResponse([
            "school" => $data
        ]);
    }
    public function edit($id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $school = $this->schoolRepository->getEdit($id);
            $admin = $this->accountRepository->getMentor();
            return view('admin.school.edit', compact(
                "school",
                "admin",
            ));
        }
    }
    public function update(SchoolRequest $request, $id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            try {
                $data = $request->except('code');
                $school = $this->schoolRepository->find($id);
                $mentor_table = $this->schoolRepository->find($id)->mentor;
                $mentor['school_id'] = $id;
                try {
                    DB::beginTransaction();
                    $this->schoolRepository->update($data, $id);
                    if ($mentor_table) {
                        $this->mentorRepository->deleteMentorSchool($request->admin, $mentor_table);
                    }
                    if (isset($request->admin)) {
                        foreach ($request->admin as $admin) {
                            $data = $this->mentorRepository->findByCond([
                                'account_id' => $admin,
                                'school_id' => $id
                            ]);
                            $mentor['account_id'] = $admin;
                            if (!isset($data)) {
                                $this->mentorRepository->create($mentor);
                            }
                        }
                    }
                    if (isset($request->grade)) {
                        $this->schoolRepository->deleteGrade($request->grade, $id);
                        foreach ($request->grade as $grades) {
                            $grade['school_id'] = $school->id;
                            $grade['name'] = $grades['name'];
                            if (isset($grades['id'])) {
                                $data = $this->gradeRepository->findByCond([
                                    'id' => $grades['id']
                                ]);
                                if ($data) {
                                    $this->gradeRepository->update($grade, $data->id);
                                }
                            }
                            if (isset($grades['code'])) {
                                $grade['code'] = $grades['code'];
                                $this->gradeRepository->create($grade);
                            }
                        }
                    }
                    $listGrade = $this->schoolRepository->find($id)->grades;
                    if (empty($request->grade) && !empty($listGrade)) {
                        $list_grade = array_column($listGrade->toArray(), 'id');
                        foreach($list_grade as $grade){
                            $this->gradeRepository->destroy($grade);
                        }
                    }
                    DB::commit();
                    return $this->sendResponse([
                        "school" => $data,
                        "url" => url("/admin/school")
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
            $check = $this->schoolRepository->checkDelete($id);
            if ($check) {
                try {
                    $this->schoolRepository->destroy($id);
                    return $this->sendResponse(__("response.delete"));
                } catch (\Exception $exception) {
                    return $this->sendError($exception->getMessage());
                }
            }
            return $this->sendError(__('response.error_school_delete'));
        }
    }
}
