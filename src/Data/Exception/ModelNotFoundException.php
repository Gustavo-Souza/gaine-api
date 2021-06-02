<?php

declare(strict_types=1);

namespace App\Data\Exception;

use Exception;

class ModelNotFoundException extends Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
