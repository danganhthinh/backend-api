<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\IllustrationRequest;
use App\Repositories\IllustrationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IllustrationsController extends BaseController
{
    protected $illustrationRepository;
    public function __construct(IllustrationRepository $illustrationRepository)
    {
        $this->illustrationRepository = $illustrationRepository;
    }
    public function index()
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $avatar = $this->illustrationRepository->getAll();
            return view('admin.avatar.index', compact('avatar'));
        }
    }

    // public function store(Request $request)
    // {
    //     $avatar = $request->file('avatar');
    //     $saveAvatar = $this->illustrationRepository->saveAvatar($avatar);
    //     $data = $request->all();
    //     $data['image'] = $saveAvatar;
    //     return $data['image'];
    //     try {
    //         $this->illustrationRepository->create($data);
    //         return $data['image'];
    //         // return $this->sendResponse(__('response.create'));
    //     } catch (\Exception $exception) {
    //         $this->illustrationRepository->deleteAvatar($saveAvatar);
    //         return $this->sendError($exception->getMessage());
    //     }
    // }
    public function show($id)
    {
        $data = $this->illustrationRepository->find($id);
        return $this->sendResponse([
            "avatar" => $data
        ]);
    }
    public function fetch(Request $request)
    {
        $avatar = $this->illustrationRepository->getAll();
        return view("admin.avatar.grid", compact("avatar"));
    }
    public function update(IllustrationRequest $request)
    {
        if (Auth::user()->role_id == Consts::ADMIN) {
            $id = $request->id;
            $avatar = $request->file('avatar');
            $saveAvatar = $this->illustrationRepository->changeAvatar($avatar, $id);
            // $data = $request->all();
            $data['image'] = $saveAvatar;
            try {
                $this->illustrationRepository->update($data, $id);
                return $this->sendResponse(__('response.create'), $saveAvatar);
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }
    }
    // public function destroy($id)
    // {
    //     try {
    //         $this->illustrationRepository->destroy($id);
    //         return $this->sendResponse(__('response.delete'));
    //     } catch (\Exception $exception) {
    //         return $this->sendError($exception->getMessage());
    //     }
    // }
}
