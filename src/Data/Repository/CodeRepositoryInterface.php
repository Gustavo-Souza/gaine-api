<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Model\Code;

interface CodeRepositoryInterface
{
    public function create(
        int $userId,
        string $streamerCode,
        string $code
    ): Code;
}
