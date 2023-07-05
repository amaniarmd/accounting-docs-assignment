<?php
namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface DocRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $userId
     * @return Model
     */
    public function assignDocToUser($userId, $role): JsonResponse;

    /**
     * @return Collection
     */
    public function getAssignedDocs(): Builder;

    /**
     * @return mixed
     */
    public function updateExpiredDocs();

    public function getSoonestDeadlineOrSevenDaysLater();
}
