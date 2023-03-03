<?php

namespace App\Repositories;

use App\Consts;
use App\Models\SubjectScoreMonth;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SubjectScoreMonthRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected GradeRepository $gradeRepository;
    protected AccountRepository $accountRepository;
    protected SubjectRepository $subjectRepository;
    protected AccountProgressRepository $accountProgressRepository;

    public function __construct()
    {
        $this->model = new SubjectScoreMonth();
        $this->gradeRepository = new GradeRepository();
        $this->accountRepository = new AccountRepository();
        $this->subjectRepository = new SubjectRepository();
        $this->accountProgressRepository = new AccountProgressRepository();
    }

    public function findSubjectScoreMonth($subject_score_id, $account_id) {
        return $this->findByCond([
            'month' => intval(date('m')),
            'year' => intval(date('Y')),
            'subject_score_id' => $subject_score_id,
            'account_id' => $account_id
        ]);
    }

    public function learningStatistics($data): array
    {
        $accounts = $this->accountRepository->searchAccountByLearning($data);
        $subject_first = $this->subjectRepository->getFirstSubject();
        $data_subject_score = [];
        $school_year = intval($data['school_year']);
        $date_year = $this->accountRepository->getStartYear($school_year);
        if ($accounts->count() > 0) {
            $accounts = $accounts->toArray();
            foreach ($accounts['data'] as $account) {
                $subject_score_month = $this->filterByAccount($account, $date_year['startYear'], $date_year['endYear'], $subject_first->id, $school_year);
                $data_subject_score['data'][] = $subject_score_month;
            }
            unset($accounts['data']);
            return $data_subject_score + $accounts;
        }
        else {
            return [];
        }
    }

    public function learningStatisticsAccount($account, $year): array
    {
        $subjects_id = $this->subjectRepository->getAllSubjectsId();
        $data = [];
        $date_year = $this->accountRepository->getStartYear($year);
        foreach ($subjects_id as $subject_id) {
            $subject_score_month = $this->filterByAccount($account,$date_year['startYear'], $date_year['endYear'], $subject_id, $year);
            $data['data'][] = $subject_score_month;
        }
        return $data;
    }

    public function filterByAccount($account, $date_start, $date_end, $subject_id, $school_year = null)
    {
        $subject_score = $this->filterAccountByYear($account, $date_start, $date_end, $subject_id);
        $subject_name = $this->subjectRepository->find($subject_id)->name;
        $level = $subject_score->first()->level->level ?? 0;
        $number_training = $subject_score->sum('number_training');
        $total_questions = $subject_score->sum('total_questions');
        $number_correct_answers = $subject_score->sum('number_correct_answers');
        $number_wrong_answer = $subject_score->sum('number_wrong_answer');
        $video_number_learning = $subject_score->sum('video_number_learning');
        $correct_answer_video = $subject_score->sum('correct_answer_video');
        if ($number_correct_answers + $number_wrong_answer === 0)
        {
            $training_correct_rate = 0;
        }
        else {
            $training_correct_rate = $number_correct_answers/($number_correct_answers + $number_wrong_answer);
        }


        return [
            'year' => $school_year,
            'account_id' => $account['id'],
            'account_name' => $account['full_name'],
            'subject_name' => $subject_name,
            'level' => $level,
            'total_questions' => $total_questions,
            'number_training' => $total_questions,
            'number_correct_answers' => $number_correct_answers,
            'video_number_learning' => $video_number_learning,
            'correct_answer_video' => $correct_answer_video,
            'training_correct_rate' => $training_correct_rate
        ];
    }

    public function filterByAccountMonth($account, $date_start, $date_end, $subject_id, $school_year = null): array
    {
        $subject_score = $this->model
            ->where('account_id', $account['id'])
            ->where('grade_id', $account['grade_id'])
            ->where('group_id', $account['group_id'])
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<', $date_end)
            ->when($subject_id !== null, function($qr) use ($subject_id){
                return $qr->where('subject_id', $subject_id);
            })
            ->first();
        $subject_name = $this->subjectRepository->find($subject_id)->name;
        $level = $subject_score->level->level ?? 0;
        $total_questions = $subject_score->total_questions ?? 0;
        $number_training = $subject_score->number_training ?? 0;
        $number_correct_answers = $subject_score->number_correct_answers ?? 0;
        $number_wrong_answer = $subject_score->number_wrong_answer ?? 0;
        $video_number_learning = $subject_score->video_number_learning ?? 0;
        $correct_answer_video = $subject_score->correct_answer_video ?? 0;
        if ($number_correct_answers + $number_wrong_answer === 0)
        {
            $training_correct_rate = 0;
        }
        else {
            $training_correct_rate = $number_correct_answers/($number_correct_answers + $number_wrong_answer);
        }


        return [
            'year' => $school_year,
            'account_id' => $account['id'],
            'account_name' => $account['full_name'],
            'subject_name' => $subject_name,
            'level' => $level,
            'total_questions' => $total_questions,
            'number_training' => $total_questions,
            'number_correct_answers' => $number_correct_answers,
            'number_wrong_answer' => $number_wrong_answer,
            'video_number_learning' => $video_number_learning,
            'correct_answer_video' => $correct_answer_video,
            'training_correct_rate' => $training_correct_rate
        ];
    }

    public function filterAccountByYear($account, $date_start, $date_end, $subject_id = null)
    {
        return $this->model
            ->where('account_id', $account['id'])
            ->where('grade_id', $account['grade_id'])
            ->where('group_id', $account['group_id'])
            ->when($date_start != null && $date_end != null, function ($qr) use ($date_start, $date_end) {
                return $qr->whereRelation('user', 'created_at', '>=', Carbon::parse($date_start))->whereRelation('user', 'created_at', '<=', Carbon::parse($date_end));
            })
            ->when($subject_id != null, function($qr) use ($subject_id){
                return $qr->where('subject_id', $subject_id);
            })
            ->get();
    }

    public function levelSubject($account, $school_year)
    {
        $subjects = $this->subjectRepository->getSubjectBySort();
        $subjects_id = array_column($subjects->toArray(), 'id');
        $levels_subject = [];
        $date_year = $this->accountRepository->getStartYear($school_year);
        foreach ($subjects_id as $subject_id) {
            $subject_score = $this->filterAccountByYear($account, $date_year['startYear'], $date_year['endYear'] , $subject_id);
            $subject_name = $this->subjectRepository->find($subject_id)->name;
            $level_subject = $subject_score->first()->level->level ?? 0;
            $levels_subject[] = [
                'id' => $subject_id,
                'subject_name' => $subject_name,
                'level_subject' => $level_subject,
            ];
        }
        return [
            'level_total' => $this->levelTotalMonth($account, $date_year['startYear'], $date_year['endYear']),
            'level_subject' => $levels_subject
        ];
    }

    public function allTimePeriods($date_year): array
    {
        $period = CarbonPeriod::create($date_year['startYear'], '1 month', $date_year['endYear'])->month();
        $start_date = '';
        $month = '';
        $data_date = [];
        foreach ($period as $key => $dt) {
            if ($key != 0) {
                $end = $dt->format("Y-m-d");
                $start = $start_date;
                if ($key == 1) {
                    $start = $date_year['startYear'];
                    $month = Carbon::create($start)->format('m');
                }
                $data_date[] = [
                    'month' => intval($month),
                    'start_date'  => $start,
                    'end_date' => $end
                ];
                $start_date = $dt->format("Y-m-d");
                $month = $dt->format("m");
            }
        }
        $date_last = [
            'month' => (int)Carbon::create($start_date)->format('m'),
            'start_date' => $start_date,
            'end_date' => Carbon::create($date_year['endYear'])->addDay(1)->format('Y-m-d')
        ];
        $data_date[] = $date_last;
        return $data_date;
    }

    public function chartTrainingCorrect($account, $time_periods): array
    {
        $training_correct_rate = [];
        foreach ($time_periods as $time)
        {
            $subject_score_month = $this->model
                ->where('account_id', $account->id)
                ->where('grade_id', $account->grade_id)
                ->where('group_id', $account->group_id)
                ->where('created_at', '>=', $time['start_date'])
                ->where('created_at', '<', $time['end_date'])
                ->get();
            $sum_number_correct = $subject_score_month->sum('number_correct_answers');
            $sum_number_wrong = $subject_score_month->sum('number_wrong_answer');
            $total_questions = $subject_score_month->sum('total_questions');
            if ($total_questions === 0)
            {
                $training_correct_rate[] = 0;
            }
            else {
                $training_correct_rate[] = $sum_number_correct/$total_questions;
            }
        }
        return $training_correct_rate;
    }

    public function levelTotalMonth($account, $date_start, $date_end): int
    {
        $subject_score_month = $this->model
            ->where('account_id', $account['id'])
            ->where('grade_id', $account['grade_id'])
            ->where('group_id', $account['group_id'])
            ->where('subject_id', null)
            ->where('created_at', '>=', $date_start)
            ->where('created_at', '<', $date_end)
            ->first();
        return $subject_score_month->level->level ?? 0;
    }
}
