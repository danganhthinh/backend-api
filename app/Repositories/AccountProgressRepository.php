<?php

namespace App\Repositories;

use App\Consts;
use App\Models\AccountProgress;
use App\Models\Question;
use App\Models\SubjectScoreMonth;
use Carbon\Carbon;

class AccountProgressRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new AccountProgress();
    }

    public function countAccountProgress($class_id, $account_id, $type)
    {
        return $this->count([
            'class_id' => $class_id,
            'account_id' => $account_id,
            'type' => $type,
            'status' => Consts::STATUS_DONE
        ]);
    }


    public function countCorrectAnswer($class_id, $account_id, $type): int
    {
        $training = $this->filter([
            'class_id' => $class_id,
            'account_id' => $account_id,
            'type' => $type,
            'status' => Consts::STATUS_DONE
        ]);
        if (count($training) == 0) {
            return 0;
        }
        $count_correct = 0;
        foreach ($training as $item)
        {
            $count_correct+=count(json_decode($item->correct_answer));
        }
        return $count_correct;
    }

    public function getAnswerValue($account_id, $value, $subject_id, $type = Consts::TYPE_TRAINING): array
    {
        $answer_correct =  $this->filter([
            'account_id' => $account_id,
            'subject_id' => $subject_id,
            'type' => $type,
            'status' => Consts::STATUS_DONE
        ]);
        $data = [];
        if ($answer_correct->count() > 0)
        {
            foreach ($answer_correct as $item) {
                if ($item[$value] !== null) {
                    $data = array_unique(array_merge($data,json_decode($item[$value])));
                }
            }
        }

        return $data;
    }

    public function getAnswerValueByDate($account_id, $value, $subject_id = null, $time_periods = false): array
    {
        $answer_correct = $this->model
            ->where('account_id', $account_id)
            ->where('subject_id', $subject_id)
            ->when($time_periods !== false, function ($qr) {
                return $qr->where('created_at', '>=', Carbon::now()->firstOfMonth());
            })
            ->where('status', Consts::STATUS_DONE)
            ->get();
        $data = [];
        $data_random = [];
        if ($answer_correct->count() > 0)
        {
            foreach ($answer_correct as $item) {
                if ($item[$value] !== null) {
                    $data = array_unique(array_merge($data,json_decode($item[$value])));
                }
            }

        }
        $answer_correct_random = $this->model
            ->where('account_id', $account_id)
            ->where('subject_id', null)
            ->when($time_periods !== false, function ($qr) {
                return $qr->where('created_at', '>=', Carbon::now()->firstOfMonth());
            })
            ->where('status', Consts::STATUS_DONE)
            ->get();

        if ($answer_correct_random->count() > 0)
        {
            foreach ($answer_correct_random as $item) {
                if ($item[$value] !== null) {
                    if ($subject_id === null) {
                        $data_random = array_unique(array_merge($data_random,json_decode($item[$value])));
                    }
                    else {
                        if (in_array($subject_id, json_decode($item['subjects_id']))) {
                            $check_question_subject = Question::where('subject_id', $subject_id)->whereIn('id', json_decode($item['correct_questions']))->first();
                            if ($check_question_subject) {
                                $data_random = array_unique(array_merge($data_random, [$check_question_subject->id]));
                            }
                        }
                    }
                }
            }
        }

        $data = array_unique(array_merge($data,$data_random));

        $questions = Question::whereIn('id', $data)->where('status', Consts::ACTIVE)->get();
        if ($questions->count() > 0)
        {
            $data = array_column($questions->toArray(), 'id');
        }

        return $data;
    }

    public function filterByDate(array $array_fill, $fill, array $condition ,$date_start, $date_end) {
        return $this->model
            ->where($array_fill)
            ->whereIn($fill, $condition)
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<', $date_end)
            ->where('status', Consts::STATUS_DONE)
            ->get();
    }

    public function chartTraining($account, $time_periods): array
    {
        $number_training = [];
        foreach ($time_periods as $time)
        {
            $date_start = $time['start_date'];
            $date_end = $time['end_date'];
            $number_training[] = SubjectScoreMonth::where('account_id', $account['id'])
                ->where('grade_id', $account['grade_id'])
                ->where('group_id', $account['group_id'])
                ->when($date_start !== null && $date_end !== null, function ($qr) use ($date_start, $date_end) {
                    return $qr->whereRelation('user', 'created_at', '>=', Carbon::parse($date_start))->whereRelation('user', 'created_at', '<=', Carbon::parse($date_end));
                })
                ->get()->sum('total_questions');
        }
        return $number_training;
    }

    public function chartVideo($account, $time_periods): array
    {
        $number_video = [];
        foreach($time_periods as $time)
        {
            {
                $number_video[] = $this->filterByDate([
                    'account_id' => $account->id,
                    'grade_id' => $account->grade_id,
                    'group_id' => $account->group_id
                ],'type', [Consts::TYPE_VIDEO],$time['start_date'], $time['end_date'])->count();
            }
        }
        return $number_video;
    }
}