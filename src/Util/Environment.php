<?php

declare(strict_types=1);

namespace App\Util;

/**
 * Helper class to get values from environment.
 */
class Environment
{
    /** Get a value from $_ENV. */
    public static function get(string $key, $defaultValue = null)
    {
        if (array_key_exists($key, $_ENV) === false) {
            return $defaultValue;
        }
        if (empty($_ENV[$key])) {
            return $defaultValue;
        }

        return $_ENV[$key];
    }
}
