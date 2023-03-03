<?php

namespace App\Repositories;

use App\Models\CategorySubject;
use Illuminate\Database\Eloquent\Model;

class CategorySubjectRepository extends BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new CategorySubject();
    }
}