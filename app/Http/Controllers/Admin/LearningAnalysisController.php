<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Repositories\AccountProgressRepository;
use App\Repositories\AccountRepository;
use App\Repositories\GradeRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LevelRepository;
use App\Repositories\MentorRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectScoreMonthRepository;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Repositories\SubjectScoreRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class LearningAnalysisController extends BaseController
{
    protected AccountRepository $accountRepository;
    protected SubjectScoreRepository $subjectScoreRepository;
    protected MentorRepository $mentorRepository;
    protected schoolRepository $schoolRepository;
    protected GroupRepository $groupRepository;
    protected GradeRepository $gradeRepository;
    protected SubjectRepository $subjectRepository;
    protected LevelRepository $levelRepository;
    protected SubjectScoreMonthRepository $subjectScoreMonthRepository;
    protected AccountProgressRepository $accountProgressRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
        $this->subjectScoreRepository = new SubjectScoreRepository();
        $this->mentorRepository = new MentorRepository();
        $this->schoolRepository = new SchoolRepository();
        $this->groupRepository = new GroupRepository();
        $this->gradeRepository = new GradeRepository();
        $this->subjectRepository = new SubjectRepository();
        $this->levelRepository = new LevelRepository();
        $this->subjectScoreMonthRepository = new SubjectScoreMonthRepository();
        $this->accountProgressRepository = new AccountProgressRepository();
    }

    public function index(): string
    {
        $user = Auth::user();
        if ($user->role_id == Consts::MENTOR) {
            $schools_id = $this->mentorRepository->filterMentor('school_id', $user->id)->toArray();
            $schools =  $this->schoolRepository->whereIn('id', array_column($schools_id, 'school_id'));

            $groups_id = $this->mentorRepository->filterMentor('group_id', $user->id)->toArray();
            $groups = $this->groupRepository->whereIn('id', array_column($groups_id, 'group_id'));
        }
        else {
            $schools = $this->schoolRepository->getAll();
            $groups = $this->groupRepository->getAll();
        }
        return view('admin.learning.index', compact('schools', 'groups'));
    }

    public function gradeBySchoolId($school_id): \Illuminate\Http\JsonResponse
    {
        $grades = $this->gradeRepository->filter([
            'school_id' => $school_id
        ]);
        return $this->sendResponse([
            'grades' => $grades
        ]);
    }

    public function search(Request $request): string
    {
        $school_id_default = null;
        $group_id_default = null;

        $user = Auth::user();
        if ($user->role_id == Consts::MENTOR) {
            $first_school = $this->mentorRepository->firstFilled('school_id', $user->id);
            if ($first_school) {
                $school_id_default = $first_school->school_id;
            }
            else {
                $first_group = $this->mentorRepository->firstFilled('group_id');
                if ($first_group) {
                    $group_id_default = $first_group->group_id;
                }
            }
        }
        $md = Carbon::now()->format('m-d');
        if ($md >= Consts::TIME_START_YEAR) {
            $schoolYear = Carbon::now()->year;
        } else{
            $schoolYear = Carbon::now()->subYear()->year;
        }
        $data = [
            'school_id' => $request->input('school_id', $school_id_default),
            'group_id' => $request->input('group_id', $group_id_default),
            'grade_id' => $request->input('grade_id', null),
            'school_year' => $request->input('school_year', $schoolYear),
            'search' => $request->input('search', null),
        ];
        $page = $request->input('page', 1);
        $learning = $this->subjectScoreMonthRepository->learningStatistics($data);
        $data_learn = isset($learning['data']) ? $learning['data'] : [];
        $limit = isset($learning['data']) ? (int)$learning['per_page'] : Consts::PAGE;
        $total = isset($learning['data']) ? (int)$learning['total'] : 0;
        $learning = new LengthAwarePaginator($data_learn, $total, $limit, $page, ['path' => Paginator::resolveCurrentPath()]);
        return view('admin.learning.grid', compact('learning'))->render();
    }

    public function subjectScoreByAccount($account_id, $year): \Illuminate\Http\JsonResponse
    {
        $account = $this->accountRepository->find(intval($account_id));
        if (!$account) {
            return $this->sendError('Account id not exist.');
        }
        $learnings = $this->subjectScoreMonthRepository->learningStatisticsAccount($account, $year);
        return $this->sendResponse([
            'learnings' => $learnings
        ]);
    }

    public function detailLearning($account_id, $year) {

        $account = $this->accountRepository->find(intval($account_id));
        if (!$account) {
            return abort(404);
        }
        $full_name  = $account->full_name;
        $subjects = $this->subjectRepository->getSubjectBySort();
        $subjects_id = array_column($subjects->toArray(), 'id');

        // Level subject
        $level = $this->subjectScoreMonthRepository->levelSubject($account->toArray(), $year);
        $level_subject = $level['level_subject'];
        //usort($level_subject, array($this, "sortUpFuncID"));

        // Level total
        $level_total = $level['level_total'];

        $date_year = $this->accountRepository->getStartYear($year);
        $time_periods = $this->subjectScoreMonthRepository->allTimePeriods($date_year);

        // Chart number training
        $data_training = $this->accountProgressRepository->chartTraining($account, $time_periods);

        // Chart training correct answer rate
        $data_training_correct = $this->subjectScoreMonthRepository->chartTrainingCorrect($account, $time_periods);

        // Chart number video
        $data_video = $this->accountProgressRepository->chartVideo($account, $time_periods);

        return view('admin.personal-learning.index',
            compact(

                'full_name',
                'level_subject',
                'level_total',
                'time_periods',
                'data_training',
                'data_training_correct',
                'data_video'
            )
        );
    }

    public function comparisonMonth($account_id, $year, Request $request) {
        $account = $this->accountRepository->find(intval($account_id));
        if (!$account) {
            return abort(404);
        }
        $full_name  = $account->full_name;
        $date_year = $this->accountRepository->getStartYear($year);
        $time_periods = $this->subjectScoreMonthRepository->allTimePeriods($date_year);
        $month_now = Carbon::now()->format('m');
        $month1 = $request['month1'] ?? $month_now;
        $month2 = $request['month2'] ?? Carbon::now()->subMonth(1)->format('m');
        $key_month1 = array_search((int)$month1, array_column($time_periods, 'month'), true);
        $key_month2 = array_search((int)$month2, array_column($time_periods, 'month'), true);

        $time_month1 = $time_periods[$key_month1];
        $time_month2 = $time_periods[$key_month2];
        $data_comparison_month = [
            'month1' => Carbon::create($time_month1['start_date'])->format('m-Y'),
            'month2' => Carbon::create($time_month2['start_date'])->format('m-Y'),
        ];
        $subjects = $this->subjectRepository->getSubjectBySort();
        $subjects_id = array_column($subjects->toArray(), 'id');
        $data = [];
        foreach ($subjects_id as $subject_id) {
            $subject_score_month1 = $this->subjectScoreMonthRepository->filterByAccountMonth($account,$time_month1['start_date'], $time_month1['end_date'], $subject_id, $year);
            $data['month1'][] = $subject_score_month1;
            $subject_score_month2 = $this->subjectScoreMonthRepository->filterByAccountMonth($account,$time_month2['start_date'], $time_month2['end_date'], $subject_id, $year);
            $data['month2'][] = $subject_score_month2;
        }
        $level_total = [
            'month1' => $this->subjectScoreMonthRepository->levelTotalMonth($account,$time_month1['start_date'], $time_month1['end_date']),
            'month2' => $this->subjectScoreMonthRepository->levelTotalMonth($account,$time_month2['start_date'], $time_month2['end_date']),
        ];

        // sum_number_training REPLACED BY sum_total_questions
        $sum_number_training = [
            'month1' => array_sum(array_column($data['month1'], 'total_questions')),
            'month2' => array_sum(array_column($data['month2'], 'total_questions')),
        ];

        $sum_number_correct_answers = [
            'month1' => array_sum(array_column($data['month1'], 'number_correct_answers')),
            'month2' => array_sum(array_column($data['month2'], 'number_correct_answers')),
        ];

        $sum_number_total_1 = $sum_number_training['month1'];
        $sum_number_total_2 = $sum_number_training['month2'];
        $sum_training_correct_rate = [
            'month1' => $sum_number_total_1 !== 0 ? array_sum(array_column($data['month1'], 'number_correct_answers')) / $sum_number_total_1 : 0,
            'month2' => $sum_number_total_2 !== 0 ? array_sum(array_column($data['month2'], 'number_correct_answers')) / $sum_number_total_2 : 0,
        ];

        $sum_video_number_learning = [
            'month1' => array_sum(array_column($data['month1'], 'video_number_learning')),
            'month2' => array_sum(array_column($data['month2'], 'video_number_learning')),
        ];
        return view('admin.personal-learning.compare-months.grid',
            compact(
                'full_name',
                'data_comparison_month',
                'level_total',
                'data',
                'sum_number_training',
                'sum_number_correct_answers',
                'sum_training_correct_rate',
                'sum_video_number_learning'
            ))->render();
    }
}
