<?php

declare(strict_types=1);

namespace App\Actions\Streamer;

use App\Actions\Streamer\Validation\StreamerUpdateValidation;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Repository\StreamerRepositoryInterface;
use App\Exception\ValidationException;

class StreamerUpdateAction
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
        $params = StreamerUpdateValidation::validate($requestParams);

        $this->streamerRepository->update(
            $params->streamer_code,
            $params->streamer_code_updated,
            $params->streamer_name_updated
        );
    }
}
