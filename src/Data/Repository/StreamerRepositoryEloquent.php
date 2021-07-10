<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Data\Exception\ModelAlreadyExistsException;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\StreamerEntity;
use Illuminate\Database\QueryException;

class StreamerRepositoryEloquent implements StreamerRepositoryInterface
{
    public function getAll(): array
    {
        return StreamerEntity::all()->toArray();
    }

    public function create(string $streamerCode, string $streamerName): void
    {
        $entity = new StreamerEntity();
        $entity->code = $streamerCode;
        $entity->name = $streamerName;
        
        try {
            $entity->saveOrFail();
        } catch (QueryException $exception) {
            // Duplication entry
            if ($exception->getCode() === '23505') {
                throw new ModelAlreadyExistsException();
                return;
            }

            // If not, throw the original exception.
            throw $exception;
        }
    }

    public function update(
        string $streamerCode,
        string $streamerCodeUpdated,
        string $streamerNameUpdated
    ): void {
        /** @var StreamerEntity */
        $entity = StreamerEntity::query()->where('code', $streamerCode)->first();
        if ($entity === null) {
            throw new ModelNotFoundException();
        }

        $entity->code = $streamerCodeUpdated;
        $entity->name = $streamerNameUpdated;
        $entity->saveOrFail();
    }

    public function delete(string $streamerCode): void
    {
        /** @var StreamerEntity */
        $entity = StreamerEntity::query()->where('code', $streamerCode)->first();
        if ($entity === null) {
            throw new ModelNotFoundException();
        }

        $entity->delete();
    }
}
