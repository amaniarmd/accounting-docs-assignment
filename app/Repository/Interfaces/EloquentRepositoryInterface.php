<?php

namespace App\Repository\Interfaces;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @param $id
     * @return Model
     */
    public function find($id): Model;

    /**
     * @param $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function jsonResponse($data, int $statusCode = Response::HTTP_OK): JsonResponse;

    /**
     * @param $message
     * @param int $status
     * @return HttpResponseException
     */
    public function jsonErrorResponse($message, int $status = Response::HTTP_NOT_FOUND): HttpResponseException;

    /**
     * @param $attribute
     * @param array $value
     * @param $scope
     * @return Collection|null
     */
    public function findInAttribute($attribute, array $value): Builder;

    /**
     * @param Builder $builder
     * @param $attribute
     * @param $value
     * @param $id
     * @return mixed
     */
    public function updateAttribute(Builder $builder, $attribute, $value, $id = null);


    /**
     * @param Builder $builder
     * @param $attribute
     * @param $operator
     * @param $value
     * @return Builder
     */
    public function findByAttribute(Builder $builder, $attribute, $operator, $value): Builder;
}
