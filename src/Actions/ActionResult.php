<?php

declare(strict_types=1);

namespace App\Actions;

class ActionResult
{
    private $httpStatusCode;
    private $json;


    public function __construct(int $httpStatusCode = 200, string $json = '')
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->json = $json;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getJson(): string
    {
        return $this->json;
    }
}
