<?php

declare(strict_types=1);

use App\Actions\User\UserAuthAction;
use App\Data\ModelMapper\UserModelMapper;
use App\Data\Repository\AuthRepositoryEloquent;
use App\Data\Repository\AuthRepositoryInterface;
use App\Data\Repository\StreamerRepositoryEloquent;
use App\Data\Repository\StreamerRepositoryInterface;
use App\Data\Repository\UserRepositoryEloquent;
use App\Data\Repository\UserRepositoryInterface;

use function DI\create;
use function DI\get;

return [
    // Model mappers
    UserModelMapper::class => create(UserModelMapper::class),

    // Repositories
    UserRepositoryInterface::class => create(UserRepositoryEloquent::class)
        ->constructor(get(UserModelMapper::class)),
    AuthRepositoryInterface::class => create(AuthRepositoryEloquent::class)
        ->constructor(get(UserModelMapper::class)),
    StreamerRepositoryInterface::class => create(StreamerRepositoryEloquent::class),

    // Actions
    UserAuthAction::class => create(UserAuthAction::class)
        ->constructor(
            get(UserRepositoryInterface::class),
            get(AuthRepositoryInterface::class)
        )
];
