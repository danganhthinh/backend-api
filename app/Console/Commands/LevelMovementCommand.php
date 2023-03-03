<?php

namespace App\Console\Commands;

use App\Jobs\handLevelMovement;
use App\Repositories\LevelRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectScoreMonthRepository;
use App\Repositories\SubjectScoreRepository;
use Illuminate\Console\Command;

class LevelMovementCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'level:movement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Level movement update';

    /**
     * Execute the console command.
     *
     * @return int
     */

    protected SubjectRepository $subjectRepository;

    public function __construct()
    {
        $this->subjectRepository = new SubjectRepository();
        parent::__construct();
    }

    public function handle()
    {
        $subjects = $this->subjectRepository->getAll();
        if ($subjects->count() > 0) {
            foreach ($subjects as $subject) {
                $movement_by_subject = (new handLevelMovement($subject->id));
                dispatch($movement_by_subject);
            }
        }
        $movement_total = (new handLevelMovement(null));
        dispatch($movement_total);
    }
}
