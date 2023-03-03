<?php

namespace App\Repositories;

use App\Models\Illustration;
use Illuminate\Support\Facades\Storage;

class IllustrationRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected Illustration $illustration;

    public function __construct()
    {
        $this->model = new Illustration();
    }
    public function saveAvatar($avatar){
        $image_name = time() . "." . $avatar->getClientOriginalExtension();
        $validation['filename'] = $image_name;
        $avatar->storeAs('public/', $image_name);
        $image_path = env('APP_URL')."/storage/".$image_name;
        return $image_path;
    }
    public function deleteAvatar($path){
        if(Storage::exists($path)){
            Storage::delete($path);
        }
    }
    public function changeAvatar($avatar,$id){
        $image_url ='public/'. $this->getAvatarUrl($id);
        if(Storage::exists($image_url)){
            Storage::delete($image_url);
        }
        $data = $this->saveAvatar($avatar);
        return $data;
    }
    public function getAvatarUrl($id){
        $data = $this->model->find($id);
        $path = env('APP_URL')."/storage/";
        $image_path = str_replace($path, "", $data->image);
        return $image_path;
    }

    public function getByName($name) {
        return $this->findByCond([
            'name' => $name,
        ]);
    }
}
