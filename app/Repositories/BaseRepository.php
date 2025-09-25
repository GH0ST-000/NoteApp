<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all resources.
     *
     * @return mixed
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * Find resource by id.
     *
     * @return mixed
     */
    public function find(int $id, array $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Find resource by criteria.
     *
     * @return mixed
     */
    public function findBy(array $criteria, array $columns = ['*'])
    {
        return $this->model->where($criteria)->get($columns);
    }

    /**
     * Create new resource.
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update resource.
     *
     * @return mixed
     */
    public function update(int $id, array $attributes)
    {
        $model = $this->find($id);
        $model->update($attributes);

        return $model;
    }

    /**
     * Delete resource.
     */
    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }
}
