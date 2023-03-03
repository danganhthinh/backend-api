<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\VideoRequest;
use App\Repositories\SubjectRepository;
use App\Repositories\VideoRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VideoController extends BaseController
{
    protected $videoRepository;
    protected $subjectRepository;
    public function __construct(VideoRepository $videoRepository, SubjectRepository $subjectRepository)
    {
        $this->videoRepository = $videoRepository;
        $this->subjectRepository = $subjectRepository;
    }
    public function index()
    {
        $video = $this->videoRepository->getVideo(20);
        $subject = $this->subjectRepository->getSubjectBySort();
        return view("admin.video.index", compact("video", "subject"));
    }
    public function fetch(Request $request)
    {
        $video = $this->videoRepository->getVideo(20);
        return view("admin.video.grid", compact("video"));
    }
    public function listBySubject($id)
    {
        $data = $this->videoRepository->listBySubject($id);
        return $this->sendResponse([
            "video" => $data
        ]);
    }
    public function search(Request $request)
    {
        $searchKey = $request->searchKey;
        $subject_id = $request->subject_id;
        $video = $this->videoRepository->search($searchKey,$subject_id);
        return view("admin.video.grid", compact("video"));
    }
    public function store(VideoRequest $request)
    {
        $data = $request->all();
        $video = $request->file('video');
        $thumbnail = $request->file('thumbnail');
        $name_video = time();
        $name_thumbnail = time() + 1;
        $request->validate(
            [
                'title' => 'unique:videos,title,NULL,id,deleted_at,NULL',
                'thumbnail' => 'bail|image|mimes:jpeg,png|max:10240|mimetypes:image/jpeg,image/png',
                'video' => 'mimes:mp4',
            ],
            [
                'title.unique' => __('response.videoTitleunique'),
                'thumbnail.max' => __('response.thumbnail_max'),
            ]
        );
        if (isset($thumbnail)) {
            $data['thumbnail'] = $this->videoRepository->saveThumbnail($thumbnail, $name_thumbnail);
        }
        $savevideo = $this->videoRepository->saveVideo($video, $request->subject_id, $name_video);
        $data['file_path'] = $savevideo['file_path'];
        $data['duration_length'] = $savevideo['duration_length'];
        $data['account_id'] = auth()->user()->id;
        try {
            $this->videoRepository->create($data);
            return $this->sendResponse(__('response.create'));
        } catch (\Exception $exception) {
            if (isset($savevideo['file_path'])) {
                $this->videoRepository->deleteFile($savevideo['file_path']);
            }
            if (isset($data['thumbnail'])) {
                $this->videoRepository->deleteFile($data['thumbnail']);
            }
            return $this->sendError($exception->getMessage());
        }
    }
    public function show($id)
    {
        $data = $this->videoRepository->getVideoUrl($id);
        return $this->sendResponse([
            "video" => $data
        ]);
    }
    public function edit($id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $video = $this->videoRepository->find($id);
            return view('admin.video.edit', compact(
                "video",
            ));
        }
    }
    public function update(VideoRequest $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $id = $request->id;
            $data = $request->all();
            $video = $request->file('video');
            $old_video = $this->videoRepository->find($id);
            $old_title = $old_video->title;
            $name_video = time();
            if($old_title != $data['title']){
                $request->validate([
                    'title' => 'unique:videos,title,NULL,id,deleted_at,NULL',
                ],[
                    'title.unique' => __('response.videoTitleunique'),
                ]);
            }
            if ($video != null) {
                $request->validate(
                    [
                        'video' => 'mimes:mp4',
                    ],
                    [
                        'thumbnail.max' => __('response.thumbnail_max'),
                    ]
                );
                $savevideo = $this->videoRepository->changeVideo($video, $id, $request->subject_id, $name_video);
                $data['file_path'] = $savevideo['file_path'];
                $data['duration_length'] = $savevideo['duration_length'];
            }
            $name_thumbnail = time() + 1;
            if ($request->file('thumbnail') != null) {
                $request->validate([
                    'thumbnail' => 'bail|image|mimes:jpeg,png|max:10240|mimetypes:image/jpeg,image/png',
                ]);
                $data['thumbnail'] = env('APP_URL') . "/storage/" . $name_thumbnail . "." . $request->file('thumbnail')->getClientOriginalExtension();
            }
            try {
                try {
                    DB::beginTransaction();
                    $video_detail = $this->videoRepository->find($id);
                    $old_thumbnail = $video_detail->thumbnail;
                    $this->videoRepository->update($data, $id);
                    if ($request->file('thumbnail') != null) {
                        $this->videoRepository->changeThumbnail($old_thumbnail, $request->file('thumbnail'), $name_thumbnail);
                    }
                    DB::commit();
                    return $this->sendResponse(__('response.update'));
                } catch (Exception $e) {
                    DB::rollback();
                    return $this->sendError($e->getMessage());
                }
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }
    }
    public function destroy($id)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            try {
                $video = $this->videoRepository->find($id);
                $this->videoRepository->destroy($id);
                $this->videoRepository->deletevideo($video->file_path);
                return $this->sendResponse(__('response.delete'));
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }
    }
}
