<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    /**
     * @param array $queries
     * @param array $relations
     * @param array $triggers
     * @return LengthAwarePaginator|Collection
     */
    public function list(array $queries = [], array $relations = [], array $triggers = []): LengthAwarePaginator|Collection;

    /**
     * @param array $parameters
     * @return Model
     */
    public function create(array $parameters): Model;

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * @param Model $model
     * @param array $parameters
     * @return Model
     */
    public function update(Model $model, array $parameters): Model;

    /**
     * @param Builder $model
     * @return mixed
     */
    public function destroy(Builder $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function delete(Model $model);

    /**
     * @param Model $model
     * @return mixed
     */
    public function restore(Model $model);

    /**
     * @param Builder $models
     * @param array $queries
     * @return Builder
     */
    public function applyQuery(Builder $models, array $queries = []): Builder;
}
