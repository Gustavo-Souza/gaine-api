<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Actions\User\UserAuthAction;
use App\Actions\User\UserSettingsAction;
use App\Data\Model\User;
use App\Exception\ValidationException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserSettingsController
{
    /** @var UserSettingsAction */
    private $action;


    public function __construct(UserSettingsAction $action)
    {
        $this->action = $action;
    }

    public function __invoke(
        User $user,
        Request $request,
        Response $response
    ): Response {
        try {
            $this->action->__invoke($user->getId(), $request->getParsedBody());
        } catch (ValidationException $exception) {
            return $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
        }
        
        return $response->withStatus(StatusCodeInterface::STATUS_OK);
    }
}
