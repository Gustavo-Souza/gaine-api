<?php

declare(strict_types=1);

namespace App\Data\Repository;

interface UserRepositoryInterface
{
    public function getAllUsersDeviceIdWithNotificationEnabled(): array;
}