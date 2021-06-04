<?php

declare(strict_types=1);

namespace App\Actions\Streamer\Validation;

use App\Exception\ValidationException;
use stdClass;

class StreamerUpdateValidation
{
    private static $regexStreamerCode = '/^[A-Za-z0-9]{2,6}+$/';
    private static $regexStreamerName = '/^[\w]+$/';


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
        $hasStreamerCode = array_key_exists(
            'streamer_code',
            $params
        );
        $hasStreamerCodeUpdated = array_key_exists(
            'streamer_code_updated',
            $params
        );
        $hasStreamerNameUpdated = array_key_exists(
            'streamer_name_updated',
            $params
        );

        return
            $hasStreamerCode &&
            $hasStreamerCodeUpdated &&
            $hasStreamerNameUpdated;
    }

    private static function isParamsValid(array $params): bool
    {
        return
            $params['streamer_code'] !== null &&
            $params['streamer_code_updated'] !== null &&
            $params['streamer_name_updated'] !== null;
    }

    private static function sanitizeAndClean(array $params): array
    {
        $arguments = [
            'streamer_code' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => [
                    'regexp' => self::$regexStreamerCode
                ]
            ],
            'streamer_code_updated' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => [
                    'regexp' => self::$regexStreamerCode
                ]
            ],
            'streamer_name_updated' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => [
                    'regexp' => self::$regexStreamerName
                ]
            ]
        ];

        $paramsSanitized = filter_var_array($params, FILTER_SANITIZE_STRING);
        return filter_var_array($paramsSanitized, $arguments);
    }
}
