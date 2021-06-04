<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;

interface UserRepositoryInterface
{
    public function getAllUsersDeviceIdWithNotificationEnabled(): array;
  
    /** @throws ModelNotFoundException */
    public function findByFirebaseAuthenticationId(
        string $firebaseAuthenticationId
    ): User;

    public function create(
        string $firebase_authentication_id,
        string $firebase_authentication_name,
        string $firebase_cloud_messaging_device_id
    ): User;

    public function update(
        User $user,
        string $firebase_authentication_id,
        string $firebase_authentication_name,
        string $firebase_cloud_messaging_device_id
    ): void;

    public function setNotificationEnabled(int $userId, bool $enabled): void;
}
