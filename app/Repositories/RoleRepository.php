<?php

namespace App\Repositories;

use App\Consts;
use App\Models\Role;

class RoleRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;
    public function __construct()
    {
        $this->model = new Role();
    }
    public function getAdminMentor(){
        return $this->model->where("id","!=",Consts::STUDENT)->get();
    }
}
