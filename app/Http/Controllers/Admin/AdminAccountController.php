<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\MentorRequest;
use App\Repositories\AccountRepository;
use App\Repositories\GroupRepository;
use App\Repositories\MentorRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SchoolRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminAccountController extends BaseController
{
    protected $accountRepository;
    protected $mentorRepository;
    protected $schoolRepository;
    protected $groupRepository;
    protected $roleRepository;

    public function __construct(AccountRepository $accountRepository, MentorRepository $mentorRepository, SchoolRepository $schoolRepository, GroupRepository $groupRepository, RoleRepository $roleRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->mentorRepository = $mentorRepository;
        $this->schoolRepository = $schoolRepository;
        $this->groupRepository = $groupRepository;
        $this->roleRepository = $roleRepository;
    }
    public function index()
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $admin = $this->accountRepository->getAllAdmin(20);
            return view("admin.admin.index", compact(
                "admin",
            ));
        }
    }
    public function institutionByMentor($id)
    {
        $institution = $this->accountRepository->getInstitutionAdmin($id);
        return $this->sendResponse([
            "institution" => $institution
        ]);
    }
    public function search(Request $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            if ($request->perPage) {
                $perPage = $request->perPage;
            } else $perPage = 20;
            $searchKey = $request->searchKey;
            $admin = $this->accountRepository->searchAdmin($searchKey, $perPage);
            return view('admin.admin.grid', compact('admin'))->render();
        }
    }
    public function render_data()
    {
        $admin = $this->accountRepository->getAllAdmin(20);
        return view('admin.admin.grid', compact('admin'));
    }
    public function create()
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $role = $this->roleRepository->getAdminMentor();
            return view('admin.admin.add', compact(
                "role"
            ));
        }
    }

    public function store(MentorRequest $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $request['admin_id'] = Consts::ADMIN_CODE . $request->code;
            $request->validate([
                'admin_id' => 'unique:accounts,student_code,NULL,id,deleted_at,NULL',
            ]);
            try {
                $data = $request->all();
                $data['password'] = $request->input('password', Consts::PASSWORD_DEFAULT);
                $data['display_password'] = $data['password'];
                $data['student_code'] = $request['admin_id'];
                DB::beginTransaction();
                $this->accountRepository->create($data);
                DB::commit();
                return $this->sendResponse([
                    "url" => url("/admin/mentor")
                ]);
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->sendError($exception->getMessage());
            }
        }
    }
    public function edit($id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $admin = $this->accountRepository->getAdmin($id);
            $institution = $this->accountRepository->listInstitutionByMentor($id);
            return view('admin.admin.edit', compact(
                "admin",
                "institution"
            ));
        }
    }
    public function update(MentorRequest $request, $id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            try {
                $data = $request->only('full_name', 'email');
                try {
                    DB::beginTransaction();
                    $this->accountRepository->update($data, $id);
                    DB::commit();
                    return $this->sendResponse([
                        "url" => url("/admin/mentor")
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
            $check = $this->accountRepository->getInstitutionAdmin($id)->toArray();
            if (empty($check)) {
                try {
                    $this->accountRepository->destroy($id);
                    return $this->sendResponse(__('response.delete'));
                } catch (\Exception $exception) {
                    return $this->sendError($exception->getMessage());
                }
            }
        }
    }
}
