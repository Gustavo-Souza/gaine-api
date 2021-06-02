<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\ActionResult;
use App\Actions\User\Validation\UserSettingsValidation;
use App\Data\Repository\UserRepositoryInterface;
use App\Exception\ValidationException;
use Exception;
use stdClass;

class UserSettingsAction
{
    /** @var UserRepositoryInterface */
    private $userRepository;


    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function __invoke(int $userId, array $requestParams): ActionResult
    {
        try {
            $params = UserSettingsValidation::validate($requestParams);
            return $this->updateUserSettings($userId, $params);
        } catch (ValidationException $exception) {
            return $this->badRequest($exception);
        } catch (Exception $exception) {
            return $this->error($exception);
        }
        
        return new ActionResult(200);
    }


    private function updateUserSettings(
        int $userId,
        stdClass $params
    ): ActionResult {
        $notificationEnabled = $params->notification;

        $this->userRepository->setNotificationEnabled(
            $userId,
            $notificationEnabled
        );

        return new ActionResult(200);
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
