<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;

interface AuthRepositoryInterface
{
    public function save(int $userId, string $jwtToken): void;

    /** @throws ModelNotFoundException */
    public function getUserByToken(string $jwtToken): User;
}
