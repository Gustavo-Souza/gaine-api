<?php

declare(strict_types=1);

namespace App\Actions\User\Validation;

use App\Exception\ValidationException;
use stdClass;

class UserAuthValidation
{
    private static $regexFirebaseAuthenticationId = '/^[\w]+$/';
    private static $regexFirebaseAuthenticationName = "/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/";
    private static $regexFirebaseCloudMessagingDeviceId = '/^[\w\-:]+$/';


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
        $hasFirebaseAuthenticationId = array_key_exists(
            'firebase_authentication_id',
            $params
        );
        $hasFirebaseAuthenticationName = array_key_exists(
            'firebase_authentication_name',
            $params
        );
        $hasFirebaseCloudMessagingDeviceId = array_key_exists(
            'firebase_cloud_messaging_device_id',
            $params
        );

        return
            $hasFirebaseAuthenticationId &&
            $hasFirebaseAuthenticationName &&
            $hasFirebaseCloudMessagingDeviceId;
    }

    private static function isParamsValid(array $params): bool
    {
        return
            $params['firebase_authentication_id'] !== null &&
            $params['firebase_authentication_name'] !== null &&
            $params['firebase_cloud_messaging_device_id'] !== null;
    }

    private static function sanitizeAndClean(array $params): array
    {
        $arguments = [
            'firebase_authentication_id' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => [
                    'regexp' => self::$regexFirebaseAuthenticationId
                ]
            ],
            'firebase_authentication_name' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => [
                    'regexp' => self::$regexFirebaseAuthenticationName
                ]
            ],
            'firebase_cloud_messaging_device_id' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => [
                    'regexp' => self::$regexFirebaseCloudMessagingDeviceId
                ]
            ]
        ];

        $paramsSanitized = filter_var_array($params, FILTER_SANITIZE_STRING);
        return filter_var_array($paramsSanitized, $arguments);
    }
}
