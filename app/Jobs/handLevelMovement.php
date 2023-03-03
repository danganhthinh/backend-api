<?php

namespace App\Jobs;

use App\Consts;
use App\Repositories\AccountProgressRepository;
use App\Repositories\CategorySubjectRepository;
use App\Repositories\LevelRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectScoreMonthRepository;
use App\Repositories\SubjectScoreRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class handLevelMovement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $subject_id;
    protected SubjectScoreRepository $subjectScoreRepository;
    protected QuestionRepository $questionRepository;
    protected LevelRepository $levelRepository;
    protected SubjectScoreMonthRepository $subjectScoreMonthRepository;
    protected AccountProgressRepository $accountProgressRepository;

    public function __construct($subject_id)
    {
        $this->subjectScoreMonthRepository = new SubjectScoreMonthRepository();
        $this->subjectScoreRepository = new SubjectScoreRepository();
        $this->questionRepository = new QuestionRepository();
        $this->levelRepository = new LevelRepository();
        $this->accountProgressRepository = new AccountProgressRepository();
        $this->subject_id = $subject_id;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle(): bool
    {
        try {
            DB::beginTransaction();
            $subject_id = $this->subject_id;
            if ($subject_id === null) {
                $question_total = $this->questionRepository->count([
                    'status' => Consts::ACTIVE,
                ]);
            }
            else {
                $question_total = $this->questionRepository->count([
                    'status' => Consts::ACTIVE,
                    'subject_id' => $subject_id
                ]);
            }
            $month_now = Carbon::now()->format('m');
            $year_now = Carbon::now()->format('Y');

            $subject_score_all = $this->subjectScoreRepository->filter([
                'subject_id' => $subject_id
            ]);
            if ($subject_score_all->count() > 0 && $question_total > 0) {
                foreach ($subject_score_all as $subject_score) {
                    if($subject_score->corrects_id) {
                        if ($subject_id === null) {
                            $corrects_id = $this->subjectScoreRepository->correctsId($subject_score->account_id);
                        }
                        else {
                            $corrects_id = $this->accountProgressRepository->getAnswerValueByDate($subject_score->account_id, 'correct_questions', $subject_id);
                        }

                        $question_active = array_column($this->questionRepository->whereInActive((array)$corrects_id), 'id');

                        $percent_correct = count($question_active)*100/$question_total;
                        $level_new = $this->levelRepository->checkLevel($percent_correct);
                        $this->subjectScoreRepository->update([
                            'average_score' => $percent_correct,
                            'level_id' => $level_new->id,
                            'correct_answer' => count($question_active),
                            'corrects_id' => json_encode($question_active),
                        ], $subject_score->id);

                        $subject_score_month = $this->subjectScoreMonthRepository->findByCond([
                            'subject_id' => $subject_id,
                            'month' => (int)$month_now,
                            'year' => (int)$year_now,
                            'account_id' => $subject_score->account_id,
                        ]);
                        if ($subject_score_month) {
                            if ($subject_score_month->corrects_id) {
                                $corrects_id_month = $this->accountProgressRepository->getAnswerValueByDate($subject_score->account_id, 'correct_questions', $subject_id, true);
                                $question_month_active = array_column($this->questionRepository->whereInActive($corrects_id_month), 'id');
                                $this->subjectScoreMonthRepository->update([
                                    'correct_answer' => count($question_month_active),
                                    'corrects_id' => json_encode($question_month_active),
                                    'average_score' => $percent_correct,
                                    'level_id' => $level_new->id,
                                ], $subject_score_month->id);
                            }
                        }
                    }
                }
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }


}
