<?php

namespace App\Repositories;

use App\Models\Mentor;

class MentorRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    public function __construct()
    {
        $this->model = new Mentor();
    }
    public function deleteMentorSchool($admin, $mentor_table)
    {
        $listAdmin = $mentor_table->whereNotIn("account_id", $admin);
        foreach ($listAdmin as $admin) {
            $this->model->destroy($admin->id);
        }
    }

    public function firstFilled($filled, $account_id = null) {
        return $this->model
            ->where($filled, '<>', null)
            ->when($account_id !== null, function ($qr) use ($account_id) {
                return $qr->where('account_id', $account_id);
            })
            ->orderBy('created_at', 'asc')
            ->first();
    }

    public function filterMentor($filled, $account_id) {
        return $this->model
            ->where($filled, '<>', null)
            ->where('account_id', $account_id)
            ->get();
    }
    public function getMentorByGroup($group_id){
        return $this->model->where("group_id", $group_id)->get();
    }
    public function getMentorBySchool($school_id){
        return $this->model->where("school_id", $school_id)->get();
    }
}
