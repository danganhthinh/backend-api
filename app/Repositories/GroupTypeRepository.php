<?php

namespace App\Repositories;

use App\Models\GroupType;

class GroupTypeRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    public function __construct()
    {
        $this->model = new GroupType();
    }
    public function search($searchKey){
        return $this->model->where('name', 'like', '%' . $searchKey . '%')->get();
    }
}
