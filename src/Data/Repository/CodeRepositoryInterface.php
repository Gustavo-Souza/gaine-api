<?php

declare(strict_types=1);

namespace App\Data\Repository;

interface CodeRepositoryInterface
{
    public function getTotalCodesSent(): int;
    public function getTotalCodesSentByUser(int $userId): int;
}
