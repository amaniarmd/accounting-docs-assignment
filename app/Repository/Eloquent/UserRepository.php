<?php

namespace App\Repository\Eloquent;

use App\Enums\TableColumns;
use App\Models\User;
use App\Repository\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * PolicyRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getBlacklistDocs($userId)
    {
        $user = $this->find($userId);

        return $user->blacklistDocs()->pluck(TableColumns::ID);
    }
}
