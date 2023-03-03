<?php

namespace App\Repositories;

use App\Consts;
use App\Models\Grade;
use App\Models\Group;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AccountRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected $mentor;
    protected $grade;
    protected $school;
    protected $group;
    protected $schoolYear;

    public function __construct()
    {
        $this->model = new User();
        $this->grade = new GradeRepository();
        $this->school = new SchoolRepository();
        $this->group = new GroupRepository();
        $this->mentor = new MentorRepository();
    }

    public function searchAccountByLearning($data)
    {
        $query = $this->model
            ->where('role_id', Consts::STUDENT)
            ->where('status', Consts::ACTIVE);

        if (isset($data['school_id']) && $data['school_id'] != '') {
            $school_id = intval($data['school_id']);
            $grades = $this->grade->filter([
                'school_id' => $school_id,
            ]);
            if ($grades->count() > 0) {
                $grades_id = array_column($grades->toArray(), 'id');
                $query = $query->whereIn('grade_id', $grades_id);
            } else {
                $query = $query->whereIn('grade_id', []);
            }
        }

        if (isset($data['group_id']) && $data['group_id'] != '') {
            $group_id = intval($data['group_id']);
            $query = $query->where('group_id', $group_id);
        }

        if (isset($data['grade_id']) && $data['grade_id'] != '') {
            $grade_id = intval($data['grade_id']);
            $query = $query->where('grade_id', $grade_id);
        }

        if (isset($data['search'])) {
            $search = $data['search'];
            $query = $query->where('full_name', 'LIKE', '%' . $search . '%');
        }

        if (isset($data['school_year']) && $data['school_year'] != '') {
            $date_year = $this->getStartYear($data['school_year']);
            $query = $query->where('created_at', '>=', Carbon::parse($date_year['startYear']))->where('created_at', '<=', Carbon::parse($date_year['endYear']));
        }
        return $query->paginate(Consts::PAGE);
    }

    public function getMail($email)
    {
        $email = $this->model::whereRaw("BINARY `email`='{$email}'")->first();
        if (!$email) {
            return null;
        }
        return $email;
    }
    public function getUserByMentor($id, $searchKey = null, $schoolYear = null)
    {
        $list = $this->model->find($id)->mentors;
        $day = $this->getStartYear($schoolYear);
        $startYear = null;
        $endYear = null;
        if ($day) {
            $startYear = $day['startYear'];
            $endYear = $day['endYear'];
        }
        $list_schoolID = array_column($list->toArray(), 'school_id');
        $listGrade = Grade::whereIn('school_id', $list_schoolID)->get();
        $grade = array_column($listGrade->toArray(), 'id');
        $school_user = $this->model->whereIn('grade_id', $grade)
            ->when(!empty($searchKey), function ($query) use ($searchKey) {
                return $query->where('full_name', 'like', '%' . $searchKey . '%');
            })
            ->when(!empty($startYear), function ($query) use ($startYear) {
                return $query->where('created_at', '>=', $startYear);
            })
            ->when(!empty($endYear), function ($query) use ($endYear) {
                return $query->where('created_at', '<=', $endYear);
            })
            ->where('role_id', Consts::STUDENT)
            ->get();
        $school_user = $this->getSchoolUser($school_user);

        $list_groupID = array_column($list->toArray(), 'group_id');
        $group_user = $this->model->whereIn('group_id', $list_groupID)
            ->when(!empty($searchKey), function ($query) use ($searchKey) {
                return $query->where('full_name', 'like', '%' . $searchKey . '%');
            })
            ->when(!empty($startYear), function ($query) use ($startYear) {
                return $query->where('created_at', '>=', $startYear);
            })
            ->when(!empty($endYear), function ($query) use ($endYear) {
                return $query->where('created_at', '<=', $endYear);
            })
            ->where('role_id', Consts::STUDENT)
            ->get();
        $group_user = $this->getGroupUser($group_user);

        foreach ($group_user as $user) {
            $school_user[] = $user;
        }
        foreach ($school_user as $user) {
            $user['age'] = $this->getAge($user);
        }
        $user = $school_user;
        return $user;
    }
    public function getAllUser($perPage)
    {
        $data = $this->model->where('role_id', Consts::STUDENT)->paginate($perPage);
        foreach ($data as $user) {
            $user['age'] = $this->getAge($user);
        }
        $user = $this->getSchoolUser($data);
        return $user;
    }
    public function searchAllUser($school_year_id, $school_id, $grade_id, $searchKey, $group_id, $schoolYear)
    {
        if (!empty($school_year_id) && empty($school_id)) {
            $listSchool = $this->schoolYear->find($school_year_id)->schools;
            $school = array_column($listSchool->toArray(), 'id');
            $listGrade = Grade::whereIn('school_id', $school)->get();
            $data = $this->getUserByGrade($searchKey, $listGrade, $grade_id = null, $group_id = null, $schoolYear);
        } elseif (!empty($school_id) && empty($grade_id)) {
            $listGrade = $this->school->find($school_id)->grades;
            if (!$listGrade->isEmpty()) {
                $data = $this->getUserByGrade($searchKey, $listGrade, $grade_id = null, $group_id = null, $schoolYear);
            } else {
                $grade = [];
                $data = $this->model->whereIn('grade_id', $grade)->paginate(20);
            }
        } elseif (!empty($grade_id)) {
            $data = $this->getUserByGrade($searchKey, $listGrade = null, $grade_id, $group_id = null, $schoolYear);
        } elseif (!empty($group_id)) {
            $data = $this->getUserByGrade($searchKey, $listGrade = null, $grade_id = null, $group_id, $schoolYear);
        } else {
            $data = $this->getUserByGrade($searchKey, $listGrade = null, $grade_id = null, $group_id, $schoolYear);
        }
        foreach ($data as $user) {
            $user['age'] = $this->getAge($user);
        }
        $user = $this->getSchoolUser($data);
        return $user;
    }
    public function getUserByGrade($searchKey, $listGrade = null, $grade_id = null, $group_id = null, $schoolYear = null)
    {
        if ($listGrade != null) {
            $grade = array_column($listGrade->toArray(), 'id');
        } else {
            $grade = null;
        }
        $day = $this->getStartYear($schoolYear);
        if (isset($day)) {
            $startYear = $day['startYear'];
            $endYear = $day['endYear'];
        }
        $data = $this->model
            ->when(!empty($grade), function ($query) use ($grade) {
                return $query->whereIn('grade_id', $grade);
            })
            ->when(!empty($searchKey), function ($query) use ($searchKey) {
                return $query->where('full_name', 'like', '%' . $searchKey . '%');
            })
            ->when(!empty($grade_id), function ($query) use ($grade_id) {
                return $query->where('grade_id',  $grade_id);
            })
            ->when(!empty($group_id), function ($query) use ($group_id) {
                return $query->where('group_id',  $group_id);
            })
            ->when(!empty($startYear), function ($query) use ($startYear) {
                return $query->where('created_at', '>=', $startYear);
            })
            ->when(!empty($endYear), function ($query) use ($endYear) {
                return $query->where('created_at', '<=', $endYear);
            })
            ->where('role_id', Consts::STUDENT)
            ->paginate(20);
        return $data;
    }
    public function getStartYear($schoolYear)
    {
        $day = null;
        if ($schoolYear != null) {
            $day['startYear'] = (new Carbon('first day of April' . $schoolYear))->format('Y-m-d');
            $day['endYear'] = (new Carbon('last day of March' . $schoolYear + 1))->format('Y-m-d');
        }
        return $day;
    }
    public function getAllAdmin($perPage)
    {
        $admin = $this->model->where('role_id', '!=', 1)
            ->orderBy('id', 'asc')
            ->paginate($perPage);
        foreach ($admin as $institution) {
            $institution['institution'] = $this->institution($institution);
        }
        return $admin;
    }
    public function getMentor()
    {
        $mentor = $this->mentor->getAll()->toArray();
        $listMentorID = array_unique(array_column($mentor, "account_id"));
        $admin = $this->model->where('role_id', '=', Consts::MENTOR)->whereNotIn("id",$listMentorID)->get();
        return $admin;
    }
    public function searchAdmin($searchKey, $perPage)
    {
        $mentor = [];
        $group = $this->group->searchGroup($searchKey);
        foreach ($group as $gr) {
            $listMentor = $this->mentor->getMentorByGroup($gr->id);
            foreach ($listMentor as $admin) {
                $mentor[] = $admin->account_id;
            }
        }
        $school = $this->school->searchSchool($searchKey);
        foreach ($school as $sc) {
            $listMentor = $this->mentor->getMentorBySchool($sc->id);
            foreach ($listMentor as $admin) {
                $mentor[] = $admin->account_id;
            }
        }
        $listAdmin = array_unique($mentor);
        $adminSearch = $this->model
            ->where('full_name', 'like', '%' . $searchKey . '%')
            ->where('role_id', '!=', Consts::STUDENT)
            ->whereNotIn('id', $listAdmin)
            ->get()->toArray();
        $adminSearchID = array_column($adminSearch, "id");
        foreach ($adminSearchID as $id) {
            $listAdmin[] = $id;
        }
        $admin = $this->model->whereIn("id",$listAdmin)->paginate($perPage);
        foreach ($admin as $institution) {
            $institution['institution'] = $this->institution($institution);
            $role_name = $this->model->find($institution->id)->role;
            $institution['role_name'] = $role_name->name;
        }
        return $admin;
    }
    public function getInstitutionAdmin($id)
    {
        $admin = $this->model->find($id);
        $institution = $this->institution($admin);
        return $institution;
    }
    public function institution($admin)
    {
        $listInstitution = $this->model->find($admin->id)->mentors;
        if (!empty($listInstitution)) {
            foreach ($listInstitution as $institution) {
                if (!empty($institution->school_id)) {
                    $list = $this->mentor->find($institution->id)->school;
                } elseif (!empty($institution->group_id)) {
                    $list = $this->mentor->find($institution->id)->group;
                }
                if (!empty($list)) {
                    $institution['name'] = $list->name;
                }
            }
        }
        return $listInstitution;
    }
    public function getSchoolUser($users)
    {
        foreach ($users as $user) {
            if (isset($user->grade_id)) {
                $school = $this->grade->find($user->grade_id)->school;
                $grade = $this->grade->find($user->grade_id);
                $user['institution'] = $school->name;
                $user['grade_name'] = $grade->name;
            } elseif (isset($user->group_id)) {
                $group = $this->group->find($user->group_id);
                $user['institution'] = $group->name;
            }
        }
        return $users;
    }
    public function getGroupUser($users)
    {
        foreach ($users as $user) {
            if (isset($user->grade_id)) {
                $school = $this->grade->find($user->grade_id)->school;
                $user['institution'] = $school->name;
            } elseif (isset($user->group_id)) {
                $group = $this->group->find($user->group_id);
                $user['institution'] = $group->name;
            }
        }
        return $users;
    }
    public function changePassword($password)
    {
        $password_hash =  Str::random(15) . Crypt::encrypt($password);
        $this->model->where('id', Auth::user()->id)->update([
            'password' => bcrypt($password),
            'display_password' => $password_hash,
            'check_first_login' => 1,
        ]);
    }
    public function changeUserPassword($password,$id)
    {
        $password_hash =  Str::random(15) . Crypt::encrypt($password);
        $this->model->where('id', $id)->update([
            'password' => bcrypt($password),
            'display_password' => $password_hash,
            'check_first_login' => 1,
        ]);
    }
    public function is_email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }
    public function getAge($user)
    {
        $data = Carbon::parse($user->birthday)->age;
        return $data;
    }

    public function checkCaseInsensitiveAccount($account)
    {
        return $this->model::whereRaw("BINARY `student_code`='{$account}'")->first();
    }
    public function checkAdminCanDelete($id)
    {
        $listInstitution = $this->model->find($id)->mentors;
        if (!empty($listInstitution)) {
            dd($listInstitution);
        };
        dd($id);
    }
    public function listInstitutionByMentor($id)
    {
        $list = $this->mentor->filterWith([
            "account_id" => $id
        ]);
        $list_schoolID = array_column($list->toArray(), 'school_id');
        $listSchool = $this->school->whereIn('id', $list_schoolID);
        $list_groupID = array_column($list->toArray(), 'group_id');
        $listGroup = $this->group->whereIn('id', $list_groupID);
        foreach ($listGroup as $user) {
            $listSchool[] = $user;
        }
        // foreach ($list as $data) {
        //     if ($data->school_id) {
        //         $data['school'] = $this->school->find($data->school_id)->name;
        //     } else {
        //         $data['group'] = $this->group->find($data->group_id)->name;
        //     }
        // }
        return $listSchool;
    }
    public function changeStatus($id)
    {
        $data = $this->model->find($id);
        if ($data->status == 1) {
            $user['status'] = 0;
        } else {
            $user['status'] = 1;
        }
        $this->update($user, $id);
    }
    public function getSchoolByMentor($id)
    {
        $data = $this->model->find($id)->mentors;
        $list_schoolID = array_column($data->toArray(), 'school_id');
        return School::whereIn('id', $list_schoolID)->get();
    }
    public function getGroupByMentor($id)
    {
        $data = $this->model->find($id)->mentors;
        $list_groupID = array_column($data->toArray(), 'group_id');
        return Group::whereIn('id', $list_groupID)->get();
    }
    public function getAdmin($id)
    {
        $admin = $this->model->find($id);
        $role_name = $this->model->find($id)->role;
        $admin['role_name'] = $role_name->name;
        return $admin;
    }
    public function getUserEdit($id)
    {
        $user = $this->model->find($id);
        if ($user->grade_id != null) {
            $school = $this->grade->find($user->grade_id)->school;
            $user['school'] = $school;
            $user['grade'] = $this->grade->find($user->grade_id);
        } elseif ($user->group_id != null) {
            $user['group'] = $this->group->find($user->group_id);
        }
        return $user;
    }
    public  function checkMentorUser($admin_id, $user_id)
    {
        $user = $this->model->find($user_id);
        if (!empty($user->grade_id)) {
            $school = $this->grade->find($user->grade_id)->school;
            $mentor = $this->mentor->findByCond([
                'school_id' => $school->id,
                'account_id' => $admin_id
            ]);
            if (!empty($mentor)) {
                return true;
            } else {
                return false;
            }
        }
        if (!empty($user->group_id)) {
            $mentor = $this->mentor->findByCond([
                'group_id' => $user->group_id,
                'account_id' => $admin_id
            ]);
            if (!empty($mentor)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
