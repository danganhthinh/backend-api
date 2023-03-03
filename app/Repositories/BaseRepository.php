<?php

namespace App\Repositories;

use App\Consts;
use App\Exceptions\BadRequestException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * model property on class instances
     */
    protected $model;
    protected $msgNotFound = 'Not Found';

    /**
     * Constructor to bind model to repo
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get the associated model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Set the associated model
     * @param $model
     *
     * @return BaseRepository
     */
    public function setModel($model): BaseRepository
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return array
     */
    public function getGuarded(): array
    {
        return $this->model->getGuarded();
    }

    /**
     * @return array
     */
    public function getFillable(): array
    {
        return $this->model->getFillable();
    }

    /**
     * Get all instances of model
     * @param array|null $condition
     * @param string $sort
     * @return Collection|Model[]
     */
    public function getAll(array $condition = null, array $with = null, $sort = 'id')
    {
        return $this->model
            ->when(!empty($with), function ($q) use ($with) {
                $q->with($with);
            })
            ->when(!empty($condition), function ($q) use ($condition) {
                return $q->where($condition);
            })->orderByDesc($sort)->get();
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function getMany($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * Count all instances of model
     * @param array|null $condition
     * @return mixed
     */
    public function countAll(array $condition = null)
    {
        return $this->model->when(!empty($condition), function ($q) use ($condition) {
            return $q->where($condition);
        })->count();
    }

    /**
     * create a new record in the database
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $attributes['created_at'] = Carbon::now();
        return $this->model->create($attributes);
    }

    /**
     * create a new record in the database
     * @param array $attributes
     * @return mixed
     */
    public function insert(array $attributes)
    {
        $attributes['created_at'] = Carbon::now();
        return $this->model->insert($attributes);
    }

    /**
     * @param $attributes
     * @return bool
     */
    public function insertMany($attributes): bool
    {
        return DB::table($this->model->getTable())->insert($attributes);
    }

    /**
     * update record in the database
     * @param array $data
     * @param null $id
     * @return mixed
     */
    public function update(array $data, $id = null)
    {
        $record = $this->find($id);
        $data['updated_at'] = Carbon::now();
        return $record->update($data);
    }

    /**
     * update record in the database
     * @param array $data
     * @param array $condition
     * @return mixed
     */
    public function updateCondition(array $data, array $condition = [])
    {
        $data['updated_at'] = Carbon::now();
        return $this->model->where($condition)->update($data);
    }

    /**
     * @param array $values
     * @param array $attributes
     * @param string $key
     * @return bool
     */
    public function bulkUpdate(array $values, array $attributes, $key = 'id'): bool
    {
        if (!empty($attributes)) {
            return $this->model->whereIn($key, $values)
                ->update($attributes);
        }
        return false;
    }

    /**
     * update record in the database
     * @param array $condition
     * @param $column
     * @param $num
     * @return mixed
     */
    public function increment(array $condition, $column, $num)
    {
        return $this->model->where($condition)->increment($column, $num);
    }

    /**
     * update record in the database
     * @param array $condition
     * @param $column
     * @param $num
     * @return mixed
     */
    public function decrement(array $condition, $column, $num)
    {
        return $this->model->where($condition)->decrement($column, $num);
    }

    /**
     * remove record from the database
     * @param $id
     * @return bool|null
     * @throws Exception
     */
    public function destroy($id): ?bool
    {
        return $this->model->destroy($id);
    }

    /**
     * Check if model exists
     * @param $id
     * @return mixed
     */
    public function exists($id)
    {
        return $this->model->exists($id);
    }

    /**
     * Check if record exists
     * @param $column
     * @param $value
     * @return mixed
     */
    public function existsRecord($column, $value)
    {
        return $this->model->where($column, $value)->exists();
    }

    /**
     * show the record with the given id
     * @param $id
     * @param array|null $field
     * @return mixed
     */
    public function find($id, array $field = null)
    {
        if (!empty($field)) {
            return $this->model->find($id, $field);
        }
        return $this->model->find($id);
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     * @throws BadRequestException
     */
//    public function findOrBad($id)
//    {
//        try {
//            $result = $this->model->findOrFail($id);
//        } catch (\Exception $e) {
//            throw new BadRequestException($this->msgNotFound, Consts::CODE_SUCCESS);
//        }
//        return $result;
//    }

    /**
     * @param array $condition
     * @return mixed
     */
    public function filter(array $condition)
    {
        return $this->model->where($condition)->get();
    }

    /**
     * Eager load database relationships
     * @param array $relations
     * @return Builder
     */
    public function with(array $relations): Builder
    {
        return $this->model->with($relations);
    }

    /**
     * update or create a new record in the database
     * @param array $data
     * @param array|null $condition
     * @return mixed
     */
    public function updateOrCreate(array $data, array $condition = null)
    {
        return $this->model->updateOrCreate($condition, $data);
    }

    /**
     * The pluck method retrieves all of the values for a given key
     * @param $value
     * @param $key
     * @param null $condition
     * @return mixed
     */
    public function pluck($value, $key, $condition = null)
    {
        return $this->model
            ->when(!empty($condition), function ($q) use ($condition) {
                return $q->where($condition);
            })
            ->pluck($value, $key);
    }

    /**
     *  Get all instances of model with paginate
     * @param int $limit
     * @param null $condition
     * @param null $sort
     * @param string|null $direction
     * @return mixed
     */

    public function findByCond(array $condition = null, $with = [])
    {
        return $this->model->with($with)->where($condition)->first();
    }

    public function getPaginationByCond(array $condition = null, $with = [], $perPage = Consts::PAGE)
    {
        return $this->model->with($with)->where($condition)->paginate($perPage);
    }

    public function count(array $condition = null)
    {
        return $this->model->where($condition)->count();
    }

    public function filterWith(array $condition, $with = [])
    {
        return $this->model->with($with)->where($condition)->get();
    }

    public function whereIn($filter = null, array $conditions)
    {
        return $this->model->whereIn($filter, $conditions)->get();
    }

    public function orWhereIn($filter = null, array $conditions)
    {
        return $this->model->orWhereIn($filter, $conditions);
    }

    public function whereInAndWhere($filter = null, array $conditions, array $conditions_where)
    {
        return $this->model->whereIn($filter, $conditions)->where($conditions_where)->get();
    }
}
