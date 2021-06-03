<?php

declare(strict_types=1);

namespace App\Actions\Streamer;

use App\Actions\Streamer\Validation\StreamerDeleteValidation;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Repository\StreamerRepositoryInterface;
use App\Exception\ValidationException;

class StreamerDeleteAction
{
    /** @var StreamerRepositoryInterface */
    private $streamerRepository;


    public function __construct(StreamerRepositoryInterface $streamerRepository)
    {
        $this->streamerRepository = $streamerRepository;
    }

    /**
     * @throws ValidationException
     * @throws ModelNotFoundException
     */
    public function __invoke(array $requestParams): void
    {
        $params = StreamerDeleteValidation::validate($requestParams);
        
        $this->streamerRepository->delete($params->streamer_code);
    }
}
