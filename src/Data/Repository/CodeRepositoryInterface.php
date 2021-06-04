<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Model\Code;
use App\Data\Model\User;

interface CodeRepositoryInterface
{
    public function get(string $streamerCode, string $code): Code;
    public function getTotalCodesSent(): int;
    public function getTotalCodesSentByUser(int $userId): int;
    public function getUserFromCodeSent(string $streamerCode, string $code): User;

    public function setCodeInvalidated(int $codeId);
    
    public function create(
        int $userId,
        string $streamerCode,
        string $code
    ): Code;

    public function delete(string $streamerCode, string $code);
}
