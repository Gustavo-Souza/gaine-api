<?php

declare(strict_types=1);

namespace App\Controller\Streamer;

use App\Actions\Streamer\StreamerListAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StreamerListController
{
    /** @var StreamerListAction */
    private $action;


    public function __construct(StreamerListAction $action)
    {
        $this->action = $action;
    }

    public function __invoke(Response $response): Response
    {
        $streamersArray = $this->action->__invoke();
        $streamersJson = json_encode($streamersArray);

        $response->getBody()->write($streamersJson);
        return $response;
    }
}
