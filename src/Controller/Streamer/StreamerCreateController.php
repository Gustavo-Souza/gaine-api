<?php

declare(strict_types=1);

namespace App\Controller\Streamer;

use App\Actions\Streamer\StreamerCreateAction;
use App\Exception\ValidationException;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StreamerCreateController
{
    /** @var StreamerCreateAction */
    private $action;


    public function __construct(StreamerCreateAction $action)
    {
        $this->action = $action;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $this->action->__invoke($request->getParsedBody());
            return $response->withStatus(StatusCode::STATUS_CREATED);
        } catch (ValidationException $exception) {
            return $response->withStatus(StatusCode::STATUS_BAD_REQUEST);
        }
    }
}
