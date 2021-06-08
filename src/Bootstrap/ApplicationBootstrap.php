<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Util\Environment;
use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Dotenv\Dotenv;

class ApplicationBootstrap
{
    /** @var bool */
    private $isDebugMode = false;

    /** @var string */
    private $basePath = __DIR__ . '/../../';

    /** @var \Slim\App */
    private $app;

    /** @var EloquentBootstrap */
    private $eloquentBootstrap;

    /** @var \DI\ContainerBuilder */
    private $containerBuilder;


    public function __construct()
    {
        $this->loadEnvironmentVariables();
        $this->isDebugMode = Environment::get('APP_DEBUG', false);

        $this->loadDependencies();
        $this->enableCompilation();

        $this->initApplication();
        $this->showErrorsInDebugMode();
        $this->addMiddlewares();
        $this->addRoutes();

        // Eloquent bootstrap
        $this->eloquentBootstrap = new EloquentBootstrap();
    }

    public function run(): void
    {
        $this->app->run();
    }

    public function getApplication(): \Slim\App
    {
        return $this->app;
    }

    public function getEloquent(): EloquentBootstrap
    {
        return $this->eloquentBootstrap;
    }


    /** Used in local only. */
    private function loadEnvironmentVariables(): void
    {
        Dotenv::createImmutable($this->basePath, '.env')->safeLoad();
    }

    private function loadDependencies(): void
    {
        $path = $this->basePath . 'src/dependencies.php';
        $dependencies = (array) require $path;

        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->addDefinitions($dependencies);
    }

    /** Enable compilation in production mode. */
    private function enableCompilation(): void
    {
        if ($this->isDebugMode) {
            return;
        }

        $defaultPath = 'var/cache';
        $path = Environment::get('APP_CONTAINER_CACHE', $defaultPath);

        $this->containerBuilder->enableCompilation($this->basePath . $path);
    }

    private function initApplication(): void
    {
        $container = $this->containerBuilder->build();

        $this->app = Bridge::create($container);
    }

    private function showErrorsInDebugMode(): void
    {
        if (!$this->isDebugMode) {
            return;
        }

        $displayErrorDetails = true;
        $logErrors = true;
        $logErrorDetails = true;

        $this->app->addErrorMiddleware(
            $displayErrorDetails,
            $logErrors,
            $logErrorDetails
        );
    }

    private function addMiddlewares(): void
    {
        $middlewaresPath = $this->basePath . 'src/middlewares.php';
        $middlewares = require $middlewaresPath;

        $middlewares($this->app);
    }

    private function addRoutes(): void
    {
        $routesPath = $this->basePath . 'src/routes.php';
        $routes = require $routesPath;

        $routes($this->app);
    }
}
