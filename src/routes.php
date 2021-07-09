<?php

declare(strict_types=1);

use App\Data\Repository\AuthRepositoryInterface;
use App\Middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

return static function (Slim\App $app): void {
    // Middlewares
    $authRepository = $app->getContainer()->get(AuthRepositoryInterface::class);
    $authMiddleware = new AuthMiddleware($authRepository);

    // Routes
    $app->get('/', App\Controller\HomeController::class);
    $app->post('/users', App\Controller\User\UserAuthController::class);


    $app->group('', function (RouteCollectorProxy $group): void {
        $group->patch('/users', App\Controller\User\UserSettingsController::class);

        $group->post('/streamers', App\Controller\Streamer\StreamerCreateController::class);
    })->addMiddleware($authMiddleware);
};
