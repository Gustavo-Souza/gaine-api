<?php

declare(strict_types=1);

namespace App\Actions\User\Validation;

use App\Exception\ValidationException;
use stdClass;

class UserSettingsValidation
{
    /** @throws ValidationException */
    public static function validate(array $params): stdClass
    {
        if (self::containsAllRequiredParams($params) === false) {
            throw new ValidationException('One or more params is missing');
        }

        $paramsCleaned = self::sanitizeAndClean($params);
        if (self::isParamsValid($paramsCleaned) === false) {
            throw new ValidationException('Invalid params');
        }

        return (object) $paramsCleaned;
    }


    private static function containsAllRequiredParams(array $params): bool
    {
        $hasNotificationBoolean = array_key_exists('notification', $params);

        return $hasNotificationBoolean;
    }

    private static function isParamsValid(array $params): bool
    {
        return $params['notification'] !== null;
    }

    private static function sanitizeAndClean(array $params): array
    {
        $arguments = [
            'notification' => [
                'filter' => FILTER_VALIDATE_BOOLEAN,
                'flags' => FILTER_NULL_ON_FAILURE
            ]
        ];

        $paramsSanitized = filter_var_array($params, FILTER_SANITIZE_STRING);
        return filter_var_array($paramsSanitized, $arguments);
    }
}
