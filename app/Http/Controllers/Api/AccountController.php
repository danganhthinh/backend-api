<?php

namespace App\Http\Controllers\Api;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Models\AccountProgress;
use App\Repositories\AccountProgressRepository;
use App\Repositories\AccountRepository;
use App\Repositories\LevelRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectScoreRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends BaseController
{
    protected AccountProgressRepository $accountProgressRepository;
    protected SubjectRepository $subjectRepository;
    protected SubjectScoreRepository $subjectScoreRepository;
    protected LevelRepository $levelRepository;
    protected QuestionRepository $questionRepository;
    protected AccountRepository $accountRepository;

    public function __construct()
    {
        $this->accountProgressRepository = new AccountProgressRepository();
        $this->subjectRepository = new SubjectRepository();
        $this->subjectScoreRepository = new SubjectScoreRepository();
        $this->levelRepository = new LevelRepository();
        $this->questionRepository = new QuestionRepository();
        $this->accountRepository = new AccountRepository();
    }

    public function getSubjectsLevel(): JsonResponse
    {
        try {
            $account = Auth::user();
            $account_id = $account->id;
            $grade_id = $account->grade_id;
            $group_id = $account->group_id;
            $subjects_data = $this->subjectRepository->getAllSubjectByAccount($account_id, $grade_id, $group_id);
            $level_total = $this->subjectScoreRepository->findByCond([
                'subject_id' => null,
                'account_id' => $account_id
            ]);
            if ($level_total) {
                $rank_total = $level_total->level->toArray();
                unset($rank_total['id'], $rank_total['created_at'], $rank_total['updated_at']);
            }
            else {
                $rank_total = [
                    'level' => 0,
                    'percent_correct' => 0,
                    'thumbnail' => null,
                ];
            }
            return $this->sendResponse([
                'rank_total' => $rank_total,
                'subjects' => $subjects_data
            ]);
        }
        catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }

    public function getLevelStamp(): JsonResponse
    {
        try {
            $account = Auth::user();
            DB::beginTransaction();
            $level_collection = json_decode($account->level_collection);
            $stamp = [];
            if (count($level_collection) > 0 ) {
                $data_levels = $this->levelRepository->whereIn('id', $level_collection)->toArray();
                $levels = array_column($data_levels, 'level');
                $min_level = min($levels);
                $data_collection = [];
                for ($i = $min_level; $i <= Consts::LEVEL_MIN; $i++) {
                    $data_collection[] = $i;
                }
                sort($data_collection);
                $data_levels = $this->levelRepository->whereIn('level', $data_collection)->toArray();
                $data_collection = array_column($data_levels, 'id');
                $this->accountRepository->update([
                    'level_collection' => json_encode($data_collection)
                ], $account->id);
                $stamp = $this->unsetTimeStamp($this->levelRepository->whereIn('id', $data_collection)->toArray());
            }
            DB::commit();
            return $this->sendResponse([
                'level_stamp' => $stamp
            ]);
        }
        catch (\Exception $exception)
        {
            DB::rollback();
            return $this->sendError($exception->getMessage());
        }
    }

    public function getVideoStamp(): JsonResponse
    {
        try {
            $account = Auth::user();
            $video_progress = $this->accountProgressRepository->filter([
                'account_id' => $account->id,
                'type' => Consts::TYPE_VIDEO,
                'status' => Consts::STATUS_DONE
            ])->toArray();
            if (count($video_progress) > 0) {
                $video_progress = array_unique(array_column($video_progress, 'video_id'));
            }
            return $this->sendResponse([
                'video_stamp' => count($video_progress)
            ]);
        }
        catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }

    public function myPage(): JsonResponse|array
    {
        try {
            $account = Auth::user();
            $high_top = [];
            $poor_top = [];

            $count_subject_score = $this->subjectScoreRepository->countNotSubject($account->id);

            $sum_correct = $this->subjectScoreRepository->sumByData([
                'account_id' => $account->id,
            ], 'number_correct_answers');

            $top_subject_correct = $this->subjectScoreRepository->orderByData([
                'account_id' => $account->id,
            ],'correct_answer', 'desc', 3);

            $top_subject_poor = $this->subjectScoreRepository->orderByData([
                'account_id' => $account->id,
            ],'correct_answer', 'asc', 1, '<>');

            $max = 0;
            if (count($top_subject_correct) > 0) {
                $total = 0;
                $max = $top_subject_correct[0]
                    ->correct_answer;
                foreach ($top_subject_correct as $item)
                {
                    if ($item->correct_answer === $max && $max !== 0)
                    {
                        $total+=1;
                        if ($total > 3) {
                            break;
                        }
                        $item_data = [
                            'average_score' => $item->average_score,
                            'subject' => $item->subject->name,
                            'level' => (int)$item->level->level,
                            'number_correct_answer' => $item->correct_answer,
                            'total_questions' => $item->total_questions
                        ];
                        array_push($high_top, $item_data);
                    }
                    else {
                        break;
                    }
                }
            }
            if (count($top_subject_poor) > 0 && $count_subject_score > 1 && $top_subject_poor[0]['level_id'] !== 1) {
                if ((int)$top_subject_poor[0]->correct_answer !== (int)$max) {
                    $data = [
                        'average_score' => $top_subject_poor[0]->average_score,
                        'subject' => $top_subject_poor[0]->subject->name,
                        'level' => (int)$top_subject_poor[0]->level->level,
                        'number_correct_answers' => $top_subject_poor[0]->correct_answer,
                        'total_questions' => $top_subject_poor[0]->total_questions
                    ];
                    array_push($poor_top, $data);
                }
            }
            return $this->sendResponse([
                    'user' => $account->full_name,
                    'number_open_app' => $account->number_open_app,
                    'number_correct' => (int)$sum_correct,
                    'top_subject_correct' => $high_top,
                    'top_subject_poor' => $poor_top
                ]);
        }
        catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}