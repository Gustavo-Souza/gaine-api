<?php

declare(strict_types=1);

namespace App\Actions\Code\Validation;

use App\Exception\ValidationException;
use stdClass;

class CodeSendValidation
{
    private static $regexStreamerCode = '/^[A-Za-z0-9]{2,6}+$/';
    private static $regexCode = '/^[A-Z0-9]{5}+$/';


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
        $hasStreamerCode = array_key_exists('streamer_code', $params);
        $hasCode = array_key_exists('code', $params);

        return $hasStreamerCode && $hasCode;
    }

    private static function isParamsValid(array $params): bool
    {
        return
            $params['streamer_code'] !== null &&
            $params['code'] !== null;
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
            'code' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'flags' => FILTER_NULL_ON_FAILURE,
                'options' => [
                    'regexp' => self::$regexCode
                ]
            ]
        ];

        $paramsSanitized = filter_var_array($params, FILTER_SANITIZE_STRING);
        return filter_var_array($paramsSanitized, $arguments);
    }
}
