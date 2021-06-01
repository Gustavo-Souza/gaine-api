<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;

class HomeController
{
    public function __invoke(ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('Hello world!');
        return $response;
    }
}
