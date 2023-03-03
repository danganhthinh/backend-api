<?php

namespace App\Repositories;

use App\Models\Subject;

class SubjectRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new Subject();
    }

    public function getAllSubjectByAccount($account_id, $grade_id, $group_id): array
    {
        $subjects = $this->model->all();
        $data_subjects = [];
        $data_default = [];
        foreach ($subjects as $item)
        {
            $data = [];
            $subject_score = $item->subjectsScore
                ->where('account_id', $account_id)
                ->first();
            $data['subject_name'] = $item->name;
            $data['sort'] = $item->sort;
            $data['subject_id'] = $item->id;
            if ($subject_score !== null && $subject_score->level->level !== 0)
            {
                $data['level'] = (int)$subject_score->level->level;
                $data['correct_answer'] = (int)$subject_score->correct_answer;
                $data['thumbnail'] = $subject_score->level->thumbnail;
                $data_subjects[] = $data;
            }
            else
            {
                $data['level'] = 0;
                $data['correct_answer'] = $subject_score->correct_answer ?? 0;
                $data['thumbnail'] = $subject_score->level->thumbnail ?? null;
                $data_subjects[] = $data;
            }

        }
        usort($data_subjects, array($this, "comparatorFuncBySort"));
        return $data_subjects;
    }

    public function comparatorFuncBySort($x, $y): int
    {
        if ($x['sort'] === $y['sort'])
            return 0;
        if ($x['sort'] > $y['sort'])
            return 1;
        else
            return -1;
    }

    public function comparatorFunc($x, $y): int
    {
        if ($x['level'] === $y['level'])
            return 0;
        if ($x['level'] < $y['level'])
            return 1;
        else
            return -1;
    }

    public function getAllSubjectsId (): array
    {
        $subject_first = $this->getFirstSubject();
        $subjects = $this->model->where('id', '<>' ,$subject_first->id)->orderBy('sort', 'asc')->get()->toArray();
        return array_column($subjects, 'id');
    }

    public function getFirstSubject()
    {
        return $this->model->orderBy('sort', 'asc')->first();
    }

    public function getSubjectBySort(){
        return $this->model->orderBy('sort', 'asc')->get();
    }
}
