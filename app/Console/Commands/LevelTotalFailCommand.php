<?php

namespace App\Console\Commands;

use App\Consts;
use App\Jobs\handUpdateLevelTotal;
use App\Models\HistoryRequest;
use App\Repositories\HistoryRequestRepository;
use Illuminate\Console\Command;

class LevelTotalFailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fail:level-total';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

    protected HistoryRequestRepository $historyRequestRepository;

    public function __construct()
    {
        $this->historyRequestRepository = new HistoryRequestRepository();
        parent::__construct();
    }

    public function handle()
    {
        $level_total_fail = $this->historyRequestRepository->filter([
            'type' => HistoryRequest::TRAINING_PROGRESS,
            'status' => HistoryRequest::STATUS_FAIL
        ]);
        if ($level_total_fail->count() > 0) {
            foreach ($level_total_fail as $item) {
                $data = json_decode($item->response_data);
                $update_level_total = (new handUpdateLevelTotal($data->account_id, $data->wrong_questions, $data->wrong_questions));
                dispatch($update_level_total);
                $this->historyRequestRepository->destroy($item->id);
            }
        }
        echo 'Done.';
    }
}
