<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Exception\ModelAlreadyExistsException;

interface StreamerRepositoryInterface
{
    public function getAll(): array;

    /** @throws ModelAlreadyExistsException */
    public function create(string $streamerCode, string $streamerName): void;
}
