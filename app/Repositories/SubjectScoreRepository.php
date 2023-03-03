<?php

namespace App\Repositories;

use App\Models\SubjectScore;

class SubjectScoreRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected GradeRepository $gradeRepository;
    protected AccountRepository $accountRepository;
    protected SubjectRepository $subjectRepository;
    protected AccountProgressRepository $accountProgressRepository;

    public function __construct()
    {
        $this->model = new SubjectScore();
        $this->gradeRepository = new GradeRepository();
        $this->accountRepository = new AccountRepository();
        $this->subjectRepository = new SubjectRepository();
        $this->accountProgressRepository = new AccountProgressRepository();
    }

    public function orderByData($fill = [], $value_max, $sort = 'desc', $limit, $compare = null)
    {
        return $this->model
            ->where($fill)
            ->where('subject_id', '<>', null)
            ->when($compare !== null, function ($qr) use ($compare){
                return $qr->where('level_id', '<>', 1);
            })
            ->orderBy($value_max, $sort)->take($limit)->get();
    }

    public function countNotSubject($account_id) {
        return $this->model->where('account_id', $account_id)->where('subject_id', '<>', null)->count();
    }

    public function sumByData($fill = [], $filter) {
        return $this->model
            ->where($fill)
            ->where('subject_id', '<>', null)
            ->sum($filter);
    }

    public function correctsId($account_id): array
    {
        $data = [];
        $subject_score = $this->model->where('account_id', $account_id)->where('subject_id', '<>', null)->get();
        if ($subject_score->count() > 0) {
            foreach ($subject_score as $item) {
                if ($item->corrects_id !== null) {
                    $data = array_unique(array_merge($data,(array)json_decode($item->corrects_id)));
                }
            }
        }
        return $data;
    }

}