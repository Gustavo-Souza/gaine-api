<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;
use App\Data\Model\UserEntity;
use App\Data\ModelMapper\UserModelMapper;

class UserRepositoryEloquent implements UserRepositoryInterface
{
    /** @var UserModelMapper */
    private $mapper;


    public function __construct(UserModelMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function getAllUsersDeviceIdWithNotificationEnabled(): array
    {
        return [];
    }

    public function findByFirebaseAuthenticationId(
        string $firebaseAuthenticationId
    ): User {
        $entity = UserEntity::query()
            ->where('firebase_auth_id', $firebaseAuthenticationId)
            ->first();
        
        if ($entity === null) {
            throw new ModelNotFoundException();
        }

        return $this->mapper->toDomainModel($entity);
    }

    public function create(
        string $firebase_authentication_id,
        string $firebase_authentication_name,
        string $firebase_cloud_messaging_device_id
    ): User {
        $entity = new UserEntity();
        $entity->firebase_auth_id = $firebase_authentication_id;
        $entity->firebase_auth_name = $firebase_authentication_name;
        $entity->fcm_device_id = $firebase_cloud_messaging_device_id;
        $entity->notification = true;
        $entity->saveOrFail();

        return $this->mapper->toDomainModel($entity);
    }

    public function update(
        User $user,
        string $firebase_authentication_id,
        string $firebase_authentication_name,
        string $firebase_cloud_messaging_device_id
    ): void {
        $entity = UserEntity::query()->find($user->getId());
        $entity->firebase_auth_id = $firebase_authentication_id;
        $entity->firebase_auth_name = $firebase_authentication_name;
        $entity->fcm_device_id = $firebase_cloud_messaging_device_id;
        $entity->notification = true;
        $entity->saveOrFail();
    }

    public function setNotificationEnabled(int $userId, bool $enabled): void
    {
    }
}
