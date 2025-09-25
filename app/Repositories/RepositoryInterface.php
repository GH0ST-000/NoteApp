<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all resources.
     *
     * @return mixed
     */
    public function all(array $columns = ['*']);

    /**
     * Find resource by id.
     *
     * @return mixed
     */
    public function find(int $id, array $columns = ['*']);

    /**
     * Find resource by criteria.
     *
     * @return mixed
     */
    public function findBy(array $criteria, array $columns = ['*']);

    /**
     * Create new resource.
     *
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update resource.
     *
     * @return mixed
     */
    public function update(int $id, array $attributes);

    /**
     * Delete resource.
     */
    public function delete(int $id): bool;
}
