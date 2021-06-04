<?php

declare(strict_types=1);

namespace App\Data\Repository;

interface AuthRepositoryInterface
{
    public function save(int $userId, string $jwtToken): void;
}
