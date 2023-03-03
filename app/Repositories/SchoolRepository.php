<?php

namespace App\Repositories;

use App\Consts;
use App\Models\School;
use Illuminate\Support\Facades\Storage;

class SchoolRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected $gradeRepository;
    protected $mentorRepository;
    public function __construct()
    {
        $this->model = new School();
        $this->gradeRepository = new GradeRepository();
        $this->mentorRepository = new MentorRepository();
    }
    public function listBySchoolYear($id)
    {
        $data = $this->model->select(
            'schools.id',
            'schools.name'
        )->where('school_year_id', $id)->get();
        return $data;
    }
    public function search($searchKey, $perPage)
    {
        $grades = $this->gradeRepository->search($searchKey);
        $school = [];
        foreach ($grades as $grade) {
            $gra = $this->model->find($grade->school_id);
            if (!empty($gra)) {
                $school[] = $gra;
            }
        }
        $listSchoolID = array_unique(array_column($school, "id"));
        $schoolSearch = $this->model->where('name', 'like', '%' . $searchKey . '%')->whereNotIn("id", $listSchoolID)->get()->toArray();
        $schoolSearchID = array_column($schoolSearch, "id");
        foreach ($schoolSearchID as $id) {
            $listSchoolID[] = $id;
        }
        $data = $this->model->whereIn("id", $listSchoolID)->paginate($perPage);
        $listschool = $this->GetListSchool($data);
        return $listschool;
    }
    public function searchList($searchKey)
    {
        $school = $this->model
            ->where('name', 'like', '%' . $searchKey . '%')->get();
        foreach ($school as $data) {
            $data['grade'] = $this->model->find($data->id)->grades;
        }
        return $school;
    }
    public function listSchool($perPage)
    {
        $listschool = $this->model->paginate($perPage);
        $data = $this->GetListSchool($listschool);
        return $data;
    }
    public function getEdit($id)
    {
        $school = $this->model->find($id);
        $school['grade'] = $this->model->find($school->id)->grades;
        $school['mentor'] = $this->model->find($school->id)->mentor;
        foreach ($school['grade'] as $grade) {
            $grade['student'] = $this->gradeRepository->find($grade->id)->users->count();
        }
        foreach ($school['mentor'] as $mentor) {
            $mentor['mentor'] = $this->mentorRepository->find($mentor->id)->user;
        }
        return $school;
    }
    public function GetListSchool($listschool)
    {
        foreach ($listschool as $data) {
            $listgrade = $this->model->find($data->id)->grades;
            $data['grade'] = $listgrade;
            foreach ($listgrade as $grade) {
                $listaccout = $this->gradeRepository->find($grade->id)->users;
                $grade['student'] = $listaccout->count();
                $data['sum_student'] += $grade['student'];
            }
            $data['year'] = date_format($data['created_at'], "d-m-Y");
        }
        return $listschool;
    }
    public function deleteGrade($grade, $school_id)
    {
        $request_grade = array_column($grade, 'id');
        $listGrade = $this->model->find($school_id)->grades->whereNotIn("id", $request_grade);
        foreach ($listGrade as $grade) {
            $student = $this->gradeRepository->find($grade->id)->users->count();
            if (!$student) {
                $this->gradeRepository->destroy($grade->id);
            }
        }
    }
    public function checkDelete($id)
    {
        $school = $this->model->find($id);
        $listgrade = $this->model->find($id)->grades;
        foreach ($listgrade as $grade) {
            $listaccout = $this->gradeRepository->find($grade->id)->users;
            $grade['student'] = $listaccout->count();
            $school['sum_student'] += $grade['student'];
        }
        if ($school['sum_student']) {
            return false;
        } else {
            return true;
        }
    }
    public function searchSchool($searchKey)
    {
        return $this->model->where('name', 'like', '%' . $searchKey . '%')->get();
    }
    public function getSchoolWithGrade()
    {
        $schools = $this->model->get();
        foreach ($schools as $school) {
            $school['grade'] = $this->model->find($school->id)->grades;
        }
        return $schools;
    }
}
