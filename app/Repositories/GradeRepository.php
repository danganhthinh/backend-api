<?php

namespace App\Repositories;

use App\Models\Grade;
use Carbon\Carbon;


class GradeRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new Grade();
    }
    public function listBySchool($id)
    {
        $data = $this->model
            ->where('school_id', $id)->get();
        return $data;
    }
    public function infoGradeBySchool($id)
    {
        $listgrade = $this->listBySchool($id);
        foreach ($listgrade as $grade) {
            $listaccout = $this->model->find($grade->id)->users;
            $grade['students'] = $listaccout->count();
        }
        return $listgrade;
    }
    public function search($searchKey){
        return $this->model->where('name', 'like', '%' . $searchKey . '%')->get();
    }
    public function searchList($searchKey,$schoolID)
    {
        $data = $this->model
        ->where('school_id',$schoolID)
        ->where('name', 'like', '%' . $searchKey . '%')->get();
        return $data;
    }
    public function gradeCanDelete($id){
        $grade = $this->model->find($id)->users->count();
        if($grade){
            return true;
        }
            return false;
    }
}
