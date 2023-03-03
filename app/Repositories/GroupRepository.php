<?php

namespace App\Repositories;

use App\Consts;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class GroupRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    protected $mentorRepository;
    protected $groupTypeRepository;

    public function __construct()
    {
        $this->model = new Group();
        $this->mentorRepository = new MentorRepository();
        $this->groupTypeRepository = new GroupTypeRepository();
    }
    public function listGroup($perPage)
    {
        $listGroup = $this->model->paginate($perPage);
        foreach ($listGroup as $group) {
            $group['user'] = $this->model->find($group->id)->users->count();
            $groupType = $this->model->find($group->id)->groupType;
            if($groupType){
                $group['type'] =$groupType->name;
            }
        }
        return $listGroup;
    }
    public function search($searchKey, $perPage)
    {
        $groupType = $this->groupTypeRepository->search($searchKey);
        $group = [];
        foreach($groupType as $type){
            $grou = $this->model->where('group_type',$type->id)->get();
            if(!empty($grou)){
                foreach($grou as $g){
                    $group[] = $g;
                }
            }
        }
        $listgroupTypeID = array_unique(array_column($group,"id"));
        $groupSearch = $this->model->where('name', 'like', '%' . $searchKey . '%')->whereNotIn("id",$listgroupTypeID)->get()->toArray();
        $groupSearchID = array_column($groupSearch,"id");
        foreach($groupSearchID as $id){
            $listgroupTypeID[] = $id;
        }
        $listGroup = $this->model->whereIn("id",$listgroupTypeID)->paginate($perPage);
        foreach ($listGroup as $group) {
            $group['user'] = $this->model->find($group->id)->users->count();
            $groupType = $this->model->find($group->id)->groupType;
            $group['type'] =$groupType->name;
        }
        return $listGroup;
    }
    public function getEdit($id)
    {
        $group = $this->model->find($id);
        $mentor = $this->model->find($group->id)->mentors;
        $account_id = array_column($mentor->toArray(), 'account_id');
        $group['mentor'] = User::whereIn('id',$account_id)->get();
        return $group;
    }
    public function checkDelete($id)
    {
        $listaccout = $this->model->find($id)->users->count();
        if ($listaccout) {
            return false;
        } else {
            return true;
        }
    }
    public function searchGroup($searchKey){
        return $this->model->where('name', 'like', '%' . $searchKey . '%')->get();
    }
}
