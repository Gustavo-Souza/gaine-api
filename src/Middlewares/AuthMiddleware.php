<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Data\Exception\ModelNotFoundException;
use App\Data\Repository\AuthRepositoryInterface;
use App\Security\JwtToken;
use App\Util\Environment;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Psr7Response;

class AuthMiddleware implements MiddlewareInterface
{
    /** @var AuthRepositoryInterface */
    private $authRepository;


    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        // Verify header has 'Authorization'
        $bearer = $request->getHeaderLine('Authorization');
        if (empty($bearer)) {
            return $this->unauthorizedResponse();
        }

        // Verify format of: Bearer <token>
        $bearerArray = explode(' ', $bearer, 2);
        if (count($bearerArray) !== 2) {
            return $this->unauthorizedResponse();
        } elseif ($bearerArray[0] !== "Bearer") {
            return $this->unauthorizedResponse();
        }

        // Verify token
        $jwtToken = $bearerArray[1];

        try {
            $secret = Environment::get('JWT_SECRET');
            JwtToken::decode($jwtToken, $secret);
        } catch (SignatureInvalidException $_) {
            return $this->unauthorizedResponse();
        } catch (ExpiredException $_) {
            return $this->unauthorizedResponse();
        } catch (Exception $_) {
            return $this->unauthorizedResponse();
        }

        // Verify user exists by token
        $user = null;

        try {
            $user = $this->authRepository->getUserByToken($jwtToken);
        } catch (ModelNotFoundException $_) {
            return $this->unauthorizedResponse();
        }

        // Insert user object and user id into request attribute to retrieve
        // in the controller.
        $request = $request
            ->withAttribute('userId', $user->getId())
            ->withAttribute('user', $user);
        
        return $handler->handle($request);
    }


    private function unauthorizedResponse(): Response
    {
        return new Psr7Response(StatusCodeInterface::STATUS_UNAUTHORIZED);
    }
}
