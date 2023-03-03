<?php

namespace App\Jobs;

use App\Consts;
use App\Models\HistoryRequest;
use App\Models\Subject;
use App\Repositories\AccountProgressRepository;
use App\Repositories\HistoryRequestRepository;
use App\Repositories\LevelRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\SubjectScoreMonthRepository;
use App\Repositories\SubjectScoreRepository;
use App\Repositories\WrongQuestionRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class handUpdateLevelTotal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $account_id;
    protected $wrong_questions;
    protected $correct_questions;
    protected WrongQuestionRepository $wrongQuestionRepository;
    protected HistoryRequestRepository $historyRequestRepository;
    protected AccountProgressRepository $accountProgressRepository;
    protected QuestionRepository $questionRepository;
    protected SubjectScoreRepository $subjectScoreRepository;
    protected SubjectScoreMonthRepository $subjectScoreMonthRepository;
    protected LevelRepository $levelRepository;

    public function __construct($account_id, $wrong_questions, $correct_questions)
    {
        $this->account_id = $account_id;
        $this->wrong_questions = (array)$wrong_questions;
        $this->correct_questions = (array)$correct_questions;
        $this->wrongQuestionRepository = new WrongQuestionRepository();
        $this->historyRequestRepository = new HistoryRequestRepository();
        $this->accountProgressRepository = new AccountProgressRepository();
        $this->questionRepository = new QuestionRepository();
        $this->subjectScoreRepository = new SubjectScoreRepository();
        $this->subjectScoreMonthRepository = new SubjectScoreMonthRepository();
        $this->levelRepository = new LevelRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $account_id = $this->account_id;
            $wrong_questions = $this->wrong_questions;
            $correct_questions = $this->correct_questions;
            DB::beginTransaction();
            $data_wrong_questions = $this->wrongQuestionRepository->filter([
                'account_id' => $account_id,
            ]);
            if ($data_wrong_questions->count() > 0 ) {
                foreach ($data_wrong_questions as $item) {
                    $wrong_questions[] = $item->question_id;
                }
            }

            $training_progress = $this->accountProgressRepository->filter([
                'account_id' => $account_id,
                'type' => Consts::TYPE_TRAINING,
                'status' => Consts::STATUS_DONE
            ]);
            if ($training_progress->count() > 0 ) {
                foreach ($training_progress as $item) {
                    if ($item->correct_questions !== null) {
                        $correct_questions = array_unique(array_merge($correct_questions, (array)json_decode($item->correct_questions)));
                    }
                }
            }
            $correct_questions = array_diff($correct_questions, $wrong_questions);
            $questions_correct_active = $this->questionRepository->whereInActive($correct_questions);
            $count_total_questions = $this->questionRepository->filter([
                'status' => Consts::STATUS_DONE
            ])->count();
            $percent_correct_total = count($questions_correct_active)*100/$count_total_questions;
            $level_new = $this->levelRepository->checkLevel($percent_correct_total);

            // Update subject score
            $subject_score = $this->subjectScoreRepository->findByCond([
                'account_id' => $account_id,
                'subject_id' => null,
            ]);
            if (!$subject_score) {
                $subject_score = $this->subjectScoreRepository->create([
                    'account_id' => $account_id,
                    'subject_id' => null,
                    'average_score' => $percent_correct_total,
                ]);
            }
            $this->subjectScoreRepository->update([
                'level_id' => $level_new->id,
                'average_score' => $percent_correct_total,
                'correct_answer' => count($questions_correct_active),
                'corrects_id' => count($questions_correct_active) > 0 ? json_encode((array)array_column($questions_correct_active, 'id')) : null,
            ], $subject_score->id);

            // Update subject score month
            $month_now = Carbon::now()->format('m');
            $year_now = Carbon::now()->format('Y');
            $subject_score_month = $this->subjectScoreMonthRepository->findByCond([
                'account_id' => $account_id,
                'subject_id' => null,
                'month' => intval($month_now),
                'year' => intval($year_now)
            ]);
            if (!$subject_score_month) {
                $subject_score_month = $this->subjectScoreMonthRepository->create([
                    'subject_score_id' => $subject_score->id,
                    'account_id' => $account_id,
                    'grade_id' => $subject_score->user->grade_id,
                    'group_id' => $subject_score->user->group_id,
                    'subject_id' => null,
                    'average_score' => $percent_correct_total,
                    'month' => (int)$month_now,
                    'year' => (int)$year_now
                ]);
            }

            if ($subject_score_month->corrects_id === null) {
                $corrects_id = $this->correct_questions;
            }
            else {
                $corrects_id = array_unique(array_merge($this->correct_questions, (array)json_decode($subject_score_month->corrects_id)));
            }

            $this->subjectScoreMonthRepository->update([
                'level_id' => $level_new->id,
                'average_score' => $percent_correct_total,
                'correct_answer' => count($corrects_id),
                'corrects_id' => json_encode((array)$corrects_id)
            ], $subject_score_month->id);
            DB::commit();
            return 0;
        }
        catch (\Exception $exception)
        {
            DB::rollback();
            $data_request = [
                'account_id' => $this->account_id,
                'wrong_questions' => $this->wrong_questions,
                'correct_questions' => $this->correct_questions
            ];
            $this->historyRequestRepository->createHistory(json_encode($data_request), HistoryRequest::LEVEL_TOTAL, HistoryRequest::STATUS_FAIL, $exception->getMessage());
            return 0;
        }
    }
}
