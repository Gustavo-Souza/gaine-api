<?php

declare(strict_types=1);

namespace App\Data\Model;

class Code
{
    private $userId;
    private $streamerCode;
    private $code;


    public function __construct(int $userId, string $streamerCode, string $code)
    {
        $this->userId = $userId;
        $this->streamerCode = $streamerCode;
        $this->code = $code;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
    
    public function getStreamerCode(): string
    {
        return $this->streamerCode;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
