<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Exception\ModelAlreadyExistsException;
use App\Data\Exception\ModelNotFoundException;

interface StreamerRepositoryInterface
{
    public function getAll(): array;

    /** @throws ModelAlreadyExistsException */
    public function create(string $streamerCode, string $streamerName): void;

    /** @throws ModelNotFoundException */
    public function update(
        string $streamerCode,
        string $streamerCodeUpdated,
        string $streamerNameUpdated
    ): void;
}
