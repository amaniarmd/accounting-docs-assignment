<?php

namespace App\Repository\Eloquent;

use App\Enums\TableColumns;
use App\Repository\Interfaces\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseRepository implements EloquentRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findInAttribute($attribute, array $value): Builder
    {
        return $this->model->whereIn($attribute, $value);
    }

    public function updateAttribute(Builder $builder, $attribute, $value, $id = null)
    {
        if (!is_null($id)) {
            $builder->where(TableColumns::ID, $id);
        }

        $builder->update([$attribute => $value]);

        return $builder;
    }

    public function find($id): Model
    {
        $model = $this->model->find($id);

        if (is_null($model)) {
            $this->jsonErrorResponse("$this->model not found");
        }

        return $model;
    }

    public function jsonErrorResponse($message, int $status = Response::HTTP_NOT_FOUND): HttpResponseException
    {
        throw new HttpResponseException(
            $this->jsonResponse([
                'error' => $message,
            ], $status)
        );
    }

    public function jsonResponse($data, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    /**
     * @param $attribute
     * @param $value
     * @return Model|null
     */
    public function findByAttribute(Builder $builder, $attribute, $operator, $value): Builder
    {
        return $builder->where($attribute, $operator, $value);
    }
}
