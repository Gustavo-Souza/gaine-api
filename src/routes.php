<?php

declare(strict_types=1);

return static function (Slim\App $app): void {
    $app->get('/', App\Controller\HomeController::class);

    $app->post('/users', App\Controller\User\UserAuthController::class);
};
