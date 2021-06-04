<?php

declare(strict_types=1);

namespace App\Actions\Streamer;

use App\Actions\Streamer\Validation\StreamerCreateValidation;
use App\Data\Repository\StreamerRepositoryInterface;

class StreamerCreateAction
{
    /** @var StreamerRepositoryInterface */
    private $streamerRepository;
    
    
    public function __construct(StreamerRepositoryInterface $streamerRepository)
    {
        $this->streamerRepository = $streamerRepository;
    }
    
    public function __invoke(array $requestParams): void
    {
        $params = StreamerCreateValidation::validate($requestParams);

        $this->streamerRepository->create(
            strtoupper($params->streamer_code),
            $params->streamer_name
        );
    }
}
