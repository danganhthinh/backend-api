<?php

namespace App\Jobs;

use App\Models\HistoryRequest;
use App\Repositories\HistoryRequestRepository;
use App\Repositories\WrongQuestionRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class handUpdateWrongQuestions implements ShouldQueue
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

    public function __construct($account_id, $wrong_questions, $correct_questions)
    {
        $this->account_id = $account_id;
        $this->wrong_questions = $wrong_questions;
        $this->correct_questions = $correct_questions;
        $this->wrongQuestionRepository = new WrongQuestionRepository();
        $this->historyRequestRepository = new HistoryRequestRepository();
    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            DB::beginTransaction();
            $this->wrongQuestionRepository->updateWrongQuestions($this->account_id, $this->wrong_questions, $this->correct_questions);
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
            $this->historyRequestRepository->createHistory(json_encode($data_request), HistoryRequest::WRONG_QUESTIONS, HistoryRequest::STATUS_FAIL, $exception->getMessage());
            return 0;
        }
    }
}
