<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\GroupRequest;
use App\Models\GroupType;
use App\Repositories\AccountRepository;
use App\Repositories\GroupRepository;
use App\Repositories\GroupTypeRepository;
use App\Repositories\MentorRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupController extends BaseController
{
    protected $groupRepository;
    protected $accountRepository;
    protected $mentorRepository;
    protected $groupTypeRepository;
    public function __construct(
        GroupRepository $groupRepository,
        AccountRepository $accountRepository,
        MentorRepository $mentorRepository,
        GroupTypeRepository $groupTypeRepository
    ) {
        $this->groupRepository = $groupRepository;
        $this->accountRepository = $accountRepository;
        $this->mentorRepository = $mentorRepository;
        $this->groupTypeRepository = $groupTypeRepository;
    }
    public function index()
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $group = $this->groupRepository->listGroup(2);
            return view("admin.group.index", compact("group"));
        }
    }
    public function list()
    {
        $group = $this->groupRepository->getAll();
        return $this->sendResponse([
            "group" => $group
        ]);
    }
    public function search(Request $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            if ($request->perPage) {
                $perPage = $request->perPage;
            } else $perPage = 2;
            $searchKey = $request->searchKey;
            $group = $this->groupRepository->search($searchKey, $perPage);
            return view('admin.group.grid', compact('group'))->render();
        }
    }
    public function render_data()
    {
        $group = $this->groupRepository->listGroup(2);
        return view('admin.group.grid', compact('group'));
    }
    public function create()
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $admin = $this->accountRepository->getMentor();
            $group_type = $this->groupTypeRepository->getAll();
            return view('admin.group.add', compact("admin", "group_type"));
        }
    }
    public function store(GroupRequest $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $data = $request->all();
            try {
                DB::beginTransaction();
                $group = $this->groupRepository->create($data);
                $mentor['group_id'] = $group->id;
                if (isset($request->admin)) {
                    foreach ($request->admin as $admin) {
                        $mentor['account_id'] = $admin;
                        $this->mentorRepository->create($mentor);
                    }
                }
                DB::commit();
                return $this->sendResponse([
                    "url" => url("/admin/group")
                ]);
            } catch (\Exception $exception) {
                DB::rollback();
                return $this->sendError($exception->getMessage());
            }
        }
    }
    public function show($id)
    {
        $group = $this->groupRepository->find($id);
        return $this->sendResponse([
            "group" => $group
        ]);
    }
    public function edit($id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $group = $this->groupRepository->getEdit($id);
            $admin = $this->accountRepository->getMentor();
            $group_type = $this->groupTypeRepository->getAll();
            return view('admin.group.edit', compact(
                "group",
                "admin",
                "group_type",
            ));
        }
    }
    public function update(GroupRequest $request, $id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            try {
                $data = $request->except('code');
                // dd($data);
                $mentor_table = $this->groupRepository->find($id)->mentors;
                try {
                    DB::beginTransaction();
                    $this->groupRepository->update($data, $id);
                    if ($mentor_table) {
                        $this->mentorRepository->deleteMentorSchool($request->admin, $mentor_table);
                    }
                    if (isset($request->admin)) {
                        foreach ($request->admin as $admin) {
                            $data = $this->mentorRepository->findByCond([
                                'account_id' => $admin,
                                'group_id' => $id
                            ]);
                            $mentor['account_id'] = $admin;
                            $mentor['group_id'] = $id;
                            if (!isset($data)) {
                                $this->mentorRepository->create($mentor);
                            }
                        }
                    }
                    DB::commit();
                    return $this->sendResponse([
                        "url" => url("/admin/group")
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
            $check = $this->groupRepository->checkDelete($id);
            if ($check) {
                try {
                    $this->groupRepository->destroy($id);
                    return $this->sendResponse(__("response.delete"));
                } catch (\Exception $exception) {
                    return $this->sendError($exception->getMessage());
                }
            }
            return $this->sendError(__('response.error'));
        }
    }
}
