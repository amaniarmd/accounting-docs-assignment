<?php

namespace App\Repository\Eloquent;

use App\Enums\RoleToStatusMappings;
use App\Enums\TableColumns;
use App\Models\Doc;
use App\Repository\Interfaces\DocRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class DocRepository extends BaseRepository implements DocRepositoryInterface
{
    private $userRepository;

    /**
     * PolicyRepository constructor.
     *
     * @param Doc $model
     */
    public function __construct(Doc $model, UserRepositoryInterface $userRepository)
    {
        parent::__construct($model);
        $this->userRepository = $userRepository;
    }

    public function assignDocToUser($userId, $role): JsonResponse
    {
        $doc = $this->getNextAvailableDoc($userId);

        if (is_null($doc)) {
            $this->jsonErrorResponse('There is no Document for you as a ' . $role);
        }

        $enumArray = RoleToStatusMappings::getEnumArray();
        $this->updateAttribute($doc::query(), TableColumns::STATUS, $enumArray[$role], $doc->id);
        $docBuilder = $this->updateAttribute($doc::query(), TableColumns::ASSIGNED_TO, $userId, $doc->id);
        return $this->jsonResponse($docBuilder->first());
    }

    private function getNextAvailableDoc($userId)
    {
        return Doc::whereNull(TableColumns::ASSIGNED_TO)
            ->where(TableColumns::STATUS, RoleToStatusMappings::BASIC_STATUS)
            ->whereNotIn(TableColumns::ID, $this->userRepository->getBlacklistDocs($userId))
            ->orderByDesc(TableColumns::PRIORITY)
            ->orderBy(TableColumns::CREATED_AT)
            ->first();
    }

    public function updateExpiredDocs()
    {
        $expiredDocsBuilder = $this->findByAttribute($this->getAssignedDocs(), TableColumns::DEADLINE, '<', now());

        if (!is_null($expiredDocsBuilder->get())) {
            $this->updateAttribute($expiredDocsBuilder, TableColumns::STATUS, RoleToStatusMappings::BASIC_STATUS);
            $this->updateAttribute($expiredDocsBuilder, TableColumns::DEADLINE, null);
        }
    }

    public function getAssignedDocs(): Builder
    {
        $enumArray = RoleToStatusMappings::getEnumArray();

        return $this->findInAttribute(TableColumns::STATUS, array_values($enumArray));
    }

    public function getSoonestDeadlineOrSevenDaysLater()
    {
        $futureDocs = $this->findByAttribute($this->getAssignedDocs(), TableColumns::DEADLINE, '>', now());

        if (is_null($futureDocs->get())) {
            return Carbon::now()->addDays(7);
        }

        $futureDocsLessThanSevenDays = $this->findByAttribute($futureDocs, TableColumns::DEADLINE, '<', now()->addDays(7));
        $closestDeadline = $futureDocsLessThanSevenDays->min(TableColumns::DEADLINE);

        if (is_null($closestDeadline)) {
            return Carbon::now()->addDays(7);
        }

        return Carbon::parse($closestDeadline);
    }
}
