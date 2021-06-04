<?php

declare(strict_types=1);

namespace App\Actions\Code;

use App\Actions\Code\Validation\CodeInvalidateValidation;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;
use App\Data\PushNotification\PushNotificationInterface;
use App\Data\Repository\CodeRepositoryInterface;
use App\Exception\ValidationException;

class CodeInvalidateAction
{
    /** @var CodeRepositoryInterface */
    private $codeRepository;

    /** @var PushNotificationInterface */
    private $pushNotification;


    public function __construct(
        CodeRepositoryInterface $codeRepository,
        PushNotificationInterface $pushNotification
    ) {
        $this->codeRepository = $codeRepository;
        $this->pushNotification = $pushNotification;
    }

    /**
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function __invoke(User $user, $requestParams): void
    {
        $params = CodeInvalidateValidation::validate($requestParams);

        $code = $this->codeRepository->get(
            $params->streamer_code,
            $params->code
        );
        $userWhoSentCode = $this->codeRepository->getUserFromCodeSent(
            $code->getStreamerCode(),
            $code->getCode()
        );

        $this->codeRepository->setCodeInvalidated($code->getId());
        $this->pushNotification->sendCodeInvalidationWarningForUser(
            $user,
            $userWhoSentCode,
            $code
        );
    }
}
