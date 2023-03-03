<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all
     * @param array $condition
     * @param $sort
     * @return mixed
     */
    public function getAll(array $condition, array $with, $sort);

    /**
     * count all
     * @param array $condition
     * @return mixed
     */
    public function countAll(array $condition);

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update(array $attributes, $id);

    /**
     * @param array $condition
     * @param $column
     * @param $num
     * @return mixed
     */
    public function increment(array $condition, $column, $num);

    /**
     * @param array $condition
     * @param $column
     * @param $num
     * @return mixed
     */
    public function decrement(array $condition, $column, $num);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * With
     * @param array $relations
     * @return mixed
     */
    public function with(array $relations);

    /**
     * updateOrCreate
     * @param array $data , $id
     * @param array|null $condition
     * @return mixed
     */
    public function updateOrCreate(array $data, array $condition = null);

    /**
     * pluck
     * @param $value , $key, $condition
     * @param $key
     * @param null $condition
     * @return mixed
     */
    public function pluck($value, $key, $condition = null);

    /**
     * paginated
     * @param null $limit
     * @param null $condition
     * @return mixed
     */
//    public function paginated($limit = null, $condition = null);
}

