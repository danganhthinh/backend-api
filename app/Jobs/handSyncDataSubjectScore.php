<?php

namespace App\Jobs;

use App\Consts;
use App\Models\SubjectScore;
use App\Repositories\AccountProgressRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectScoreMonthRepository;
use App\Repositories\SubjectScoreRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class handSyncDataSubjectScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data_sync;
    protected $account_id;
    protected SubjectRepository $subjectRepo;
    protected AccountProgressRepository $accountProgressRepo;
    protected SubjectScoreRepository $subjectScoreRepo;
    protected SubjectScoreMonthRepository $subjectScoreMonthRepo;

    public function __construct($data_sync, $account_id)
    {
        $this->data_sync = $data_sync;
        $this->account_id = $account_id;
        $this->subjectRepo = new SubjectRepository();
        $this->accountProgressRepo = new AccountProgressRepository();
        $this->subjectScoreRepo = new SubjectScoreRepository();
        $this->subjectScoreMonthRepo = new SubjectScoreMonthRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subjects = $this->subjectRepo->getSubjectBySort();
        if ($this->data_sync === 'wrong_questions') {
            $data_sync_score = 'number_wrong_answer';
        }
        else {
            $data_sync_score = 'number_correct_answers';
        }
        $data_progress = $this->accountProgressRepo->filter([
            'account_id' => $this->account_id,
            'status' => Consts::STATUS_DONE
        ]);
        foreach ($subjects as $subject) {
            $subject_id = $subject->id;
            if ($data_progress->count() > 0) {
                $sum = 0;
                foreach ($data_progress->toArray() as $progress) {
                    if ($progress['type'] !== Consts::TYPE_VIDEO && $progress[$this->data_sync] !== null) {
                        if ($progress['type'] === Consts::TYPE_TRAINING && $progress['subject_id'] === $subject_id) {
                            $sum += count(json_decode($progress[$this->data_sync]));
                        }
                        elseif ($progress['type'] === Consts::TYPE_TRAINING_RANDOM) {
                            if (count(json_decode($progress['subjects_id'])) > 0) {
                                $subjects_random = json_decode($progress['subjects_id']);
                                $key_subject = array_search($subject_id, $subjects_random);
                                $question_random = json_decode($progress['questions_id'])[$key_subject];
                                if ($key_subject && in_array($question_random, json_decode($progress[$this->data_sync]))) {
                                    $sum += 1;
                                }
                            }
                        }
                    }
                }
                $subject_score= $this->subjectScoreRepo->findByCond([
                    'account_id' => $this->account_id,
                    'subject_id' => $subject_id
                ]);
                if ($subject_score) {
                    $this->subjectScoreRepo->update([
                        $data_sync_score => $sum
                    ], $subject_score->id);

                    $subject_score_month = $this->subjectScoreMonthRepo->findSubjectScoreMonth($subject_score->id, $this->account_id);
                    if ($subject_score_month) {
                        $this->subjectScoreMonthRepo->update([
                            $data_sync_score => $sum
                        ], $subject_score_month->id);
                    }
                }
            }
        }
    }
}
