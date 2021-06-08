<?php

declare(strict_types=1);

namespace Test\Integration\Data\Repository;

use App\Data\Repository\AuthRepositoryInterface;

class AuthRepositoryFake implements AuthRepositoryInterface
{
    private $db = [];


    public function save(int $userId, string $jwtToken): void
    {
        $this->db[$userId] = $jwtToken;
    }
}
