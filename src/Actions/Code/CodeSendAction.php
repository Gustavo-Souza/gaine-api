<?php

declare(strict_types=1);

namespace App\Actions\Code;

use App\Actions\Code\Validation\CodeSendValidation;
use App\Data\Exception\ModelAlreadyExistsException;
use App\Data\PushNotification\PushNotificationInterface;
use App\Data\Repository\CodeRepositoryInterface;
use App\Data\Repository\UserRepositoryInterface;
use App\Exception\ValidationException;

class CodeSendAction
{
    /** @var CodeRepositoryInterface */
    private $codeRepository;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var PushNotificationInterface */
    private $pushNotification;


    public function __construct(
        CodeRepositoryInterface $codeRepository,
        UserRepositoryInterface $userRepository,
        PushNotificationInterface $pushNotification
    ) {
        $this->codeRepository = $codeRepository;
        $this->userRepository = $userRepository;
        $this->pushNotification = $pushNotification;
    }

    /**
     * @throws ValidationException
     * @throws ModelAlreadyExistsException
     */
    public function __invoke(int $userId, $requestParams): void
    {
        $params = CodeSendValidation::validate($requestParams);

        $code = $this->codeRepository->create(
            $userId,
            strtoupper($params->streamer_code),
            strtoupper($params->code)
        );

        $usersDeviceId =
            $this->userRepository->getAllUsersDeviceIdWithNotificationEnabled();
        
        $this->pushNotification->sendCode($usersDeviceId, $code);
    }
}
