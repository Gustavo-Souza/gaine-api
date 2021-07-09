<?php

declare(strict_types=1);

namespace App\Controller\Streamer;

use App\Actions\Streamer\StreamerUpdateAction;
use App\Data\Exception\ModelNotFoundException;
use App\Exception\ValidationException;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StreamerUpdateController
{
    /** @var StreamerUpdateAction */
    private $action;


    public function __construct(StreamerUpdateAction $action)
    {
        $this->action = $action;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $this->action->__invoke($request->getParsedBody());
        } catch (ValidationException $exception) {
            return $response->withStatus(StatusCode::STATUS_BAD_REQUEST);
        } catch (ModelNotFoundException $exception) {
            return $response->withStatus(StatusCode::STATUS_NOT_FOUND);
        }
        return $response;
    }
}
