<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Model\StreamerEntity;

class StreamerRepositoryEloquent implements StreamerRepositoryInterface
{
    public function getAll(): array
    {
        // TODO: Implementation
        return [];
    }

    public function create(string $streamerCode, string $streamerName): void
    {
        $entity = new StreamerEntity();
        $entity->code = $streamerCode;
        $entity->name = $streamerName;
        $entity->saveOrFail();
    }

    public function update(
        string $streamerCode,
        string $streamerCodeUpdated,
        string $streamerNameUpdated
    ): void {
        // TODO: Implementation
    }

    public function delete(string $streamerCode): void
    {
        // TODO: Implementation
    }
}
