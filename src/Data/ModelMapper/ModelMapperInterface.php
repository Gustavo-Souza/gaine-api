<?php

declare(strict_types=1);

namespace App\Data\ModelMapper;

use Illuminate\Database\Eloquent\Model;

interface ModelMapperInterface
{
    public function toDomainModel(Model $entity);
}
