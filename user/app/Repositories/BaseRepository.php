<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return 'NoModel';
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return app($this->getModelName());
    }

    public function toResource(Model $model): JsonResource
    {
        return new JsonResource($model);
    }

    public function toCollection(Collection $collection): ResourceCollection
    {
        return new ResourceCollection($collection);
    }

    public function toJSON(LengthAwarePaginator|Collection|Model $rawData): array
    {
        $data = [];
        $meta = [];

        if ($rawData instanceof Model) {
            $data = $this->toResource($rawData);
        }

        if ($rawData instanceof Collection) {
            $data = $this->toCollection($rawData);
        }

        if ($rawData instanceof LengthAwarePaginator) {
            $data = $this->toCollection(collect($rawData->items()));
            $meta = $rawData->toArray();
            unset($meta['data']);
        }

        return [
            'data' => $data,
            'meta' => $meta,
        ];
    }

    /**
     * @param array $queries
     * @param array $relations
     * @param array $triggers
     * @return LengthAwarePaginator|Collection
     */
    public function list(array $queries = [], array $relations = [], array $triggers = []): LengthAwarePaginator|Collection
    {
        $models = $this->getModel()->query()->with($relations);
        if (isset($triggers['order_by'])) {
            $models = $models->orderBy('created_at', $triggers['order_by']);
        } else {
            $models = $models->orderBy('created_at', 'DESC');
        }
        if(isset($queries['with_trashed'])) {
            $models = $models->withTrashed();
        }
        if (isset($triggers['paginate']) && $triggers['paginate']) {
            return $this->applyQuery($models, $queries)->paginate();
        }
        return $this->applyQuery($models, $queries)->get();
    }

    /**
     * @param array $parameters
     * @return Model
     */
    public function create(array $parameters): Model
    {
        return $this->getModel()->query()->create($parameters);
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->getModel()->query()->find($id);
    }

    /**
     * @param Model $model
     * @param array $parameters
     * @return Model
     */
    public function update(Model $model, array $parameters): Model
    {
        if(isset($parameters['lock'])) {
            if($parameters['forUpdate']) {
                $model->lockForUpdate();
            } elseif($parameters['shared']) {
                $model->sharedLocked();
            }
            unset($parameters['lock']);
        }
        $model->update($parameters);
        return $model->refresh();
    }

    public function destroy(Builder $model)
    {
        return $model->forceDelete();
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function delete(Model $model)
    {
        return $model->delete();
    }

    /**
     * @param Model $model
     * @return mixed
     */
    public function restore(Model $model)
    {
        return $model->restore();
    }

    /**
     * @param Builder $models
     * @param array $queries
     * @return Builder
     */
    public function applyQuery(Builder $models, array $queries = []): Builder
    {
        foreach ($queries as $column => $value) {
            $models->where($column, $value);
        }
        return $models;
    }
}
