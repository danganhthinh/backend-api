<?php

namespace App\Repositories;

use App\Consts;
use App\Models\Subject;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;

class VideoRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected $subject;

    public function __construct()
    {
        $this->model = new Video();
        $this->subject = new SubjectRepository();
    }
    public function getVideo($perPage)
    {
        $listvideo = $this->model->paginate($perPage);
        foreach ($listvideo as $video) {
            $account = $this->model->find($video->id)->user;
            if (isset($account->full_name)) {
                $video['user_name'] = $account->full_name;
            }
            $subject = $this->subject->find($video->subject_id);
            if (isset($subject->name)) {
                $video['subject_name'] = $subject->name;
            }
        }
        return $listvideo;
    }
    public function search($searchKey, $subject_id)
    {
        $listvideo = $this->model
            ->when(!empty($searchKey), function ($query) use ($searchKey) {
                return $query->where('title', 'like', '%' . $searchKey . '%');
            })
            ->when(!empty($subject_id), function ($query) use ($subject_id) {
                return $query->where('subject_id', $subject_id);
            })
            ->paginate(20);
        foreach ($listvideo as $video) {
            $account = $this->model->find($video->id)->user;
            if (isset($account->full_name)) {
                $video['user_name'] = $account->full_name;
            }
            $subject = $this->subject->find($video->subject_id);
            if (isset($subject->name)) {
                $video['subject_name'] = $subject->name;
            }
        }
        return $listvideo;
    }
    public function saveVideo($video, $subject_id, $name)
    {
        $video_name = str_replace(' ', '', $video->getClientOriginalName());
        $validation['filename'] = $video_name;
        $video->storeAs('public/', $video_name);
        $path_video = "public/" . $video_name;
        $path = Storage::path($path_video);
        $data["duration_length"] = $this->getLengthVideo($path);
        $data["file_path"] = env('APP_URL') . "/storage/" . $video_name;
        return $data;
    }
    public function saveThumbnail($thumbnail, $name)
    {
        $image_name = $name . "." . $thumbnail->getClientOriginalExtension();
        $validation['filename'] = $image_name;
        $thumbnail->storeAs('public/', $image_name);
        $image_path = env('APP_URL') . "/storage/" . $image_name;
        return $image_path;
    }
    public function listBySubject($id)
    {
        $data = $this->model
            ->where('subject_id', $id)->get();
        return $data;
    }
    public function getLengthVideo($path)
    {
        $getID3 = new \getID3;
        $video_file = $getID3->analyze($path);
        $duration_seconds = $video_file['playtime_seconds'];
        return $duration_seconds;
    }
    public function changeVideo($video, $id, $subject_id, $name)
    {
        $video_url = 'public/' . $this->getVideoUrl($id);
        if (Storage::exists($video_url)) {
            Storage::delete($video_url);
        }
        $data = $this->savevideo($video, $subject_id, $name);
        return $data;
    }
    public function changeThumbnail($file_path, $thumbnail, $name)
    {
        $path = env('APP_URL') . "/storage/";
        $thumbnail_path = str_replace($path, "", $file_path);
        $thumbnail_url = 'public/' . $thumbnail_path;
        if (Storage::exists($thumbnail_url)) {
            Storage::delete($thumbnail_url);
        }
        $data = $this->saveThumbnail($thumbnail, $name);
        return $data;
    }
    public function getVideoUrl($id)
    {
        $data = $this->model->find($id);
        $path = env('APP_URL') . "/storage/";
        $video_path = str_replace($path, "", $data->file_path);
        return $video_path;
    }
    public function deleteFile($video_path)
    {
        $path = env('APP_URL') . "/storage/";
        $video = 'public/' . str_replace($path, "", $video_path);
        if (Storage::exists($video)) {
            Storage::delete($video);
        }
    }

    public function getAllVideoBySubject($subject_id, $video_level)
    {
        return $this->model
            ->where('subject_id', intval($subject_id))
            ->where('video_level', intval($video_level))
            ->where('status', Consts::STATUS_DONE)
            ->orderBy('video_level', 'asc')
            ->paginate(Consts::PAGE);
    }
    public function deletevideo($video_path)
    {
        $path = env('APP_URL') . "/storage/";
        $video = 'public/' . str_replace($path, "", $video_path);
        if (Storage::exists($video)) {
            Storage::delete($video);
        }
    }
}
