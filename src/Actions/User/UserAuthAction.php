<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\ActionResult;
use App\Actions\User\Validation\UserAuthValidation;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;
use App\Data\Repository\AuthRepositoryInterface;
use App\Data\Repository\UserRepositoryInterface;
use App\Exception\ValidationException;
use App\Security\JwtToken;
use App\Util\Environment;
use Exception;
use stdClass;

class UserAuthAction
{
    private const TOKEN_EXPIRATION_TIME = 60 * 60 * 24 * 5; // 5 days

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var AuthRepositoryInterface */
    private $authRepository;


    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthRepositoryInterface $authRepository
    ) {
        $this->userRepository = $userRepository;
        $this->authRepository = $authRepository;
    }

    public function __invoke(array $requestParams): ActionResult
    {
        try {
            $params = UserAuthValidation::validate($requestParams);

            $user = $this->userRepository->findByFirebaseAuthenticationId(
                $params->firebase_authentication_id
            );
            
            return $this->loginUser($user, $params);
        } catch (ModelNotFoundException $_) {
            return $this->registerUser($params);
        } catch (ValidationException $exception) {
            return $this->badRequest($exception);
        } catch (Exception $exception) {
            // Some error happened.
            return $this->error($exception);
        }
        
        return new ActionResult(200);
    }


    private function registerUser(stdClass $params): ActionResult
    {
        $user = $this->userRepository->create(
            $params->firebase_authentication_id,
            $params->firebase_authentication_name,
            $params->firebase_cloud_messaging_device_id
        );

        $secret = Environment::get('JWT_SECRET');
        $jwtToken = JwtToken::encode([], $secret, self::TOKEN_EXPIRATION_TIME);
        $this->authRepository->save($user->getId(), $jwtToken);

        $jsonArray = ['token' => $jwtToken];
        $json = json_encode($jsonArray);

        return new ActionResult(201, $json);
    }

    private function loginUser(User $user, stdClass $params): ActionResult
    {
        $this->userRepository->update(
            $user,
            $params->firebase_authentication_id,
            $params->firebase_authentication_name,
            $params->firebase_cloud_messaging_device_id
        );

        $secret = Environment::get('JWT_SECRET');
        $jwtToken = JwtToken::encode([], $secret, self::TOKEN_EXPIRATION_TIME);
        $this->authRepository->save($user->getId(), $jwtToken);

        $jsonArray = ['token' => $jwtToken];
        $json = json_encode($jsonArray);

        return new ActionResult(200, $json);
    }

    private function badRequest(ValidationException $exception): ActionResult
    {
        return new ActionResult(400);
    }

    private function error(Exception $exception): ActionResult
    {
        // TODO: Log error
        return new ActionResult(500);
    }
}
