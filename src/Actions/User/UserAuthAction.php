<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\User\Validation\UserAuthValidation;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;
use App\Data\Repository\AuthRepositoryInterface;
use App\Data\Repository\UserRepositoryInterface;
use App\Security\JwtToken;
use App\Util\Environment;
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

    public function __invoke(array $requestParams): array
    {
        $jsonArray = [];
        $params = UserAuthValidation::validate($requestParams);

        try {
            $user = $this->userRepository->findByFirebaseAuthenticationId(
                $params->firebase_authentication_id
            );
            $jsonArray = $this->loginUser($user, $params);
        } catch (ModelNotFoundException $_) {
            $jsonArray = $this->registerUser($params);
        }

        return $jsonArray;
    }


    private function registerUser(stdClass $params): array
    {
        $user = $this->userRepository->create(
            $params->firebase_authentication_id,
            $params->firebase_authentication_name,
            $params->firebase_cloud_messaging_device_id
        );

        $secret = Environment::get('JWT_SECRET');
        $jwtToken = JwtToken::encode([], $secret, self::TOKEN_EXPIRATION_TIME);
        
        $this->authRepository->save($user->getId(), $jwtToken);

        return ['token' => $jwtToken];
    }

    private function loginUser(User $user, stdClass $params): array
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

        return ['token' => $jwtToken];
    }
}
