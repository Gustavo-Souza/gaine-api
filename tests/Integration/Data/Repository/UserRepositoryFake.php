<?php

declare(strict_types=1);

namespace Test\Integration\Data\Repository;

use App\Data\Model\User;
use App\Data\Repository\UserRepositoryInterface;

class UserRepositoryFake implements UserRepositoryInterface
{
    private $autoIncrement = 1;
    private $db = [];


    public function getAllUsersDeviceIdWithNotificationEnabled(): array
    {
        return [];
    }

    public function findByFirebaseAuthenticationId(string $firebaseAuthenticationId): User
    {
        return new User();
    }

    public function create(string $firebase_authentication_id, string $firebase_authentication_name, string $firebase_cloud_messaging_device_id): User
    {
        $this->db[$firebase_authentication_id] = [
            'id' => $this->autoIncrement,
            'firebase_authentication_id' => $firebase_authentication_id,
            'firebase_authentication_name' => $firebase_authentication_name,
            'firebase_cloud_messaging_device_id' =>
                $firebase_cloud_messaging_device_id
        ];

        $user = new User(
            $this->autoIncrement,
            $$firebase_authentication_id,
            $firebase_authentication_name,
            $firebase_cloud_messaging_device_id,
            true,
            time(),
            time()
        );
        return $user;
    }

    public function update(User $user, string $firebase_authentication_id, string $firebase_authentication_name, string $firebase_cloud_messaging_device_id): void
    {
    }

    public function setNotificationEnabled(int $userId, bool $enabled): void
    {
    }
}
