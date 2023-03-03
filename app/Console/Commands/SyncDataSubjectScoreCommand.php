<?php

namespace App\Console\Commands;

use App\Jobs\handSyncDataSubjectScore;
use App\Models\User;
use Illuminate\Console\Command;

class SyncDataSubjectScoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:subject-score {data}';

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
    public function handle()
    {
        $data_sync = $this->argument('data');
        $accounts = User::all();
        foreach ($accounts as $account) {
            $handle = (new handSyncDataSubjectScore($data_sync, $account->id));
            dispatch($handle);
        }
        echo 'Done';
    }
}
