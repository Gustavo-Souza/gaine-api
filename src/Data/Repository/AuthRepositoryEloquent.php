<?php

declare(strict_types=1);

namespace APp\Data\Repository;

use App\Data\Model\AuthEntity;
use App\Data\Repository\AuthRepositoryInterface;

class AuthRepositoryEloquent implements AuthRepositoryInterface
{
    public function save(int $userId, string $jwtToken): void
    {
        $entity = AuthEntity::query()->where('user_id', $userId)->firstOrNew();
        $entity->user_id = $userId;
        $entity->token = $jwtToken;
        $entity->saveOrFail();
    }
}
