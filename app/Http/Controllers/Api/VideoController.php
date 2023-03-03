<?php

namespace App\Http\Controllers\Api;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Repositories\AccountProgressRepository;
use App\Repositories\LevelRepository;
use App\Repositories\SubjectScoreMonthRepository;
use App\Repositories\SubjectScoreRepository;
use App\Repositories\VideoRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VideoController extends BaseController
{
    protected VideoRepository $videoRepository;
    protected AccountProgressRepository $accountProgressRepository;
    protected SubjectScoreRepository $subjectScoreRepository;
    protected SubjectScoreMonthRepository $subjectScoreMonthRepository;
    protected LevelRepository $levelRepository;

    public function __construct()
    {
        $this->videoRepository = new VideoRepository();
        $this->accountProgressRepository = new AccountProgressRepository();
        $this->subjectScoreRepository = new SubjectScoreRepository();
        $this->subjectScoreMonthRepository  = new SubjectScoreMonthRepository();
        $this->levelRepository = new LevelRepository();
    }

    public function detailVideo($video_id): JsonResponse
    {
        try
        {
            $video = $this->videoRepository->find($video_id);
            if (!$video) {
                return $this->sendError('Video not exist.');
            }
            return $this->sendResponse([
                'video' => $video
            ]);
        }
        catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }

    public function getVideosBySubject(Request $request, $subject_id): JsonResponse
    {
        try {
            $video_level = intval($request->level ?? 1);
            $account = Auth::user();
            $videos_progress = $this->accountProgressRepository->filter([
                'account_id' => $account->id,
                'subject_id' => $subject_id,
                'type' => Consts::TYPE_VIDEO,
                'status' => Consts::STATUS_DONE
            ]);
            $videos = $this->videoRepository->getAllVideoBySubject($subject_id, $video_level)->toArray();
            if (count($videos_progress) > 0){
                $progress_videos_id = array_unique(array_column($videos_progress->toArray(), 'video_id'));
                foreach ($progress_videos_id as $value)
                {
                    $key_video_watched = array_search($value, array_column($videos['data'], 'id'));
                    if (count($videos['data']) > 0) {
                        $videos['data'][$key_video_watched]['watched'] = 1;
                    }
                }
            }
            if ($videos['prev_page_url'] != null) {
                $videos['prev_page_url'] = $videos['prev_page_url'].'&level='.$video_level;
            }
            if ($videos['next_page_url'] != null) {
                $videos['next_page_url'] = $videos['next_page_url'].'&level='.$video_level;
            }

            return $this->sendResponse([
                'videos' => $this->unsetTimeStamp($videos['data']),
                'video_level' => $video_level,
                'from_page' => $videos['current_page'],
                'prev_page_url' => $videos['prev_page_url'],
                'next_page_url' => $videos['next_page_url']
            ]);
        }
        catch (\Exception $exception)
        {
            return $this->sendError($exception->getMessage());
        }
    }

    public function updateProgressVideo($id): JsonResponse
    {
        try {
            $account = Auth::user();
            $video = $this->videoRepository->find($id);
            if (!$video){
                return $this->sendError('Video not exist.');
            }
            DB::beginTransaction();
            $this->accountProgressRepository->create([
                'account_id' => $account->id,
                'grade_id' => $account->grade_id,
                'group_id' => $account->group_id,
                'subject_id' => $video->subject_id,
                'video_id' => $id,
                'type'=> Consts::TYPE_VIDEO,
                'status' => Consts::STATUS_DONE
            ]);

            $subject_score = $this->subjectScoreRepository->findByCond([
                'account_id' => $account->id,
                'subject_id' => $video->subject_id,
            ]);
            $level_default = $this->levelRepository->levelDefault();

            if (!$subject_score)
            {
                $subject_score = $this->subjectScoreRepository->create([
                    'account_id' => $account->id,
                    'subject_id' => $video->subject_id,
                    'level_id' => $level_default->id
                ]);
            }
            $this->subjectScoreRepository->update([
               'video_number_learning' =>  $subject_score->video_number_learning + 1
            ], $subject_score->id);

            $month_now = Carbon::now()->format('m');
            $year_now = Carbon::now()->format('Y');
            $subject_score_month = $this->subjectScoreMonthRepository->findByCond([
                'account_id' => $account->id,
                'grade_id' => $account->grade_id,
                'group_id' => $account->group_id,
                'subject_id' => $video->subject_id,
                'month' => intval($month_now),
                'year' => intval($year_now)
            ]);
            if (!$subject_score_month)
            {
                $subject_score_month = $this->subjectScoreMonthRepository->create([
                    'account_id' => $account->id,
                    'grade_id' => $account->grade_id,
                    'group_id' => $account->group_id,
                    'subject_score_id' => $subject_score->id,
                    'subject_id' => $video->subject_id,
                    'month' => intval($month_now),
                    'year' => intval($year_now),
                    'level_id' => $level_default->id
                ]);
            }
            $this->subjectScoreMonthRepository->update([
                'video_number_learning' =>  $subject_score_month->video_number_learning + 1
            ], $subject_score_month->id);

            DB::commit();
            return $this->sendResponse('Success');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
