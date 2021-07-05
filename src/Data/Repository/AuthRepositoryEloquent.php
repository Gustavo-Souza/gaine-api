<?php

declare(strict_types=1);

namespace APp\Data\Repository;

use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\AuthEntity;
use App\Data\Model\User;
use App\Data\ModelMapper\UserModelMapper;
use App\Data\Repository\AuthRepositoryInterface;

class AuthRepositoryEloquent implements AuthRepositoryInterface
{
    /** @var UserModelMapper */
    private $userModelMapper;


    public function __construct(UserModelMapper $userModelMapper)
    {
        $this->userModelMapper = $userModelMapper;
    }

    public function save(int $userId, string $jwtToken): void
    {
        $entity = AuthEntity::query()->where('user_id', $userId)->firstOrNew();
        $entity->user_id = $userId;
        $entity->token = $jwtToken;
        $entity->saveOrFail();
    }

    public function getUserByToken(string $jwtToken): User
    {
        /** @var AuthEntity */
        $entity = AuthEntity::query()->where('token', $jwtToken)->first();
        if ($entity === null) {
            throw new ModelNotFoundException();
        }

        $userEntity = $entity->user();
        return $this->userModelMapper->toDomainModel($userEntity);
    }
}
