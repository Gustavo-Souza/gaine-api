<?php

declare(strict_types=1);

namespace Test\Integration;

use App\Bootstrap\ApplicationBootstrap;
use App\Bootstrap\EloquentBootstrap;
use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Factory\RequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\UriFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;

class AppTestCase extends TestCase
{
    /** @var EloquentBootstrap */
    private $eloquentBootstrap;

    /** @var App */
    protected $app;

    protected const GET = 'GET';
    protected const POST = 'POST';


    protected function setUp(): void
    {
        $applicationBootstrap = new ApplicationBootstrap();
        $this->app = $applicationBootstrap->getApplication();
        $this->eloquentBootstrap = $applicationBootstrap->getEloquent();

        /* $dependencies = (array) require __DIR__ . '/../../src/dependencies.php';
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions($dependencies);
        $container = $containerBuilder->build();

        $this->app = Bridge::create($container);

        $middlewares = require __DIR__ . '/../../src/middlewares.php';
        $middlewares($this->app);

        $routes = require __DIR__ . '/../../src/routes.php';
        $routes($this->app); */
    }

    /** Authenticates and return the JWT token. */
    protected function authenticate(): String
    {
        // Make the request with params.
        $paramsAuthentication = [
            'firebase_authentication_id' => 'auth_test',
            'firebase_authentication_name' => 'Test',
            'firebase_cloud_messaging_device_id' => 'device_test'
        ];
        $responseAuthentication = $this->post('/users', $paramsAuthentication);

        // Get the jwt token from json
        $authenticationJson = $responseAuthentication->getBody()->__toString();
        $jsonArray = json_decode($authenticationJson, true);
        $jwtToken = $jsonArray['token'];

        return $jwtToken;
    }

    /** Makes a request with GET method. */
    protected function get(
        string $url,
        array $params = [],
        array $headers = []
    ): ResponseInterface {
        return $this->requestWithQueryParams('GET', $url, $params, $headers);
    }

    /** Makes a request with POST method. */
    protected function post(
        string $url,
        array $params = [],
        array $headers = []
    ): ResponseInterface {
        return $this->requestWithBodyParams('POST', $url, $params, $headers);
    }

    /** Makes a request with PUT method. */
    protected function put(
        string $url,
        array $params = [],
        array $headers = []
    ): ResponseInterface {
        return $this->requestWithBodyParams('PUT', $url, $params, $headers);
    }

    /** Makes a request with GET method. */
    protected function patch(
        string $url,
        array $params = [],
        array $headers = []
    ): ResponseInterface {
        return $this->requestWithBodyParams('PATCH', $url, $params, $headers);
    }

    protected function cleanTables(array $tables = []): void
    {
        foreach ($tables as $table) {
            $this->eloquentBootstrap->get($table)->delete();
        }
    }


    private function requestWithQueryParams(
        string $method,
        string $url,
        array $params = [],
        array $headers = []
    ): ResponseInterface {
        $urlWithParams = '';
        if (empty($params) === false) {
            $urlWithParams = $url . '?' . http_build_query($params);
        } else {
            $urlWithParams = $url;
        }

        $requestFactory = new RequestFactory();
        $request = $requestFactory->createRequest($method, $urlWithParams);
        foreach ($headers as $headerKey => $headerValue) {
            $request = $request->withHeader($headerKey, $headerValue);
        }

        return $this->app->handle($request);
    }

    private function requestWithBodyParams(
        string $method,
        string $url,
        array $params = [],
        array $headers = []
    ): ResponseInterface {
        $paramsString = http_build_query($params);
        $uriFactory = new UriFactory();
        $streamFactory = new StreamFactory();

        $uri = $uriFactory->createUri($url);
        $headers = new Headers($headers);
        $cookies = [];
        $serverParams = [];
        $body = $streamFactory->createStream($paramsString);

        $request = new Request(
            $method,
            $uri,
            $headers,
            $cookies,
            $serverParams,
            $body
        );
        $request = $request->withParsedBody($params);

        return $this->app->handle($request);
    }
}
