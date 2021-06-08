<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Actions\User\UserAuthAction;
use App\Exception\ValidationException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserAuthController
{
    /** @var UserAuthAction */
    private $action;


    public function __construct(UserAuthAction $action)
    {
        $this->action = $action;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $jsonArray = $this->action->__invoke($request->getParsedBody());
            $json = json_encode($jsonArray);

            $response->getBody()->write($json);
            return $response->withStatus(StatusCodeInterface::STATUS_OK);
        } catch (ValidationException $exception) {
            return $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        return $response->withStatus(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
    }
}
