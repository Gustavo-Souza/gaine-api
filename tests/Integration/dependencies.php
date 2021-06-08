<?php

declare(strict_types=1);

use App\Actions\User\UserAuthAction;
use App\Data\Repository\AuthRepositoryInterface;
use App\Data\Repository\UserRepositoryInterface;
use Test\Integration\Data\Repository\AuthRepositoryFake;
use Test\Integration\Data\Repository\UserRepositoryFake;

use function DI\create;
use function DI\get;

return [

    // Repositories
    UserRepositoryInterface::class => create(UserRepositoryFake::class),
    AuthRepositoryInterface::class => create(AuthRepositoryFake::class),

    // Actions
    UserAuthAction::class => create(UserAuthAction::class)
        ->constructor(
            get(UserRepositoryInterface::class),
            get(AuthRepositoryInterface::class)
        )
];
