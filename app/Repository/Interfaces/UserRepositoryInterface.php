<?php
namespace App\Repository\Interfaces;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $userId
     * @return mixed
     */
    public function getBlacklistDocs($userId);
}
