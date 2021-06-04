<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\User\Validation\UserSettingsValidation;
use App\Data\Repository\UserRepositoryInterface;
use App\Exception\ValidationException;

class UserSettingsAction
{
    /** @var UserRepositoryInterface */
    private $userRepository;


    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /** @throws ValidationException */
    public function __invoke(int $userId, array $requestParams): void
    {
        $params = UserSettingsValidation::validate($requestParams);
        
        $this->userRepository->setNotificationEnabled(
            $userId,
            $params->notification
        );
    }
}
