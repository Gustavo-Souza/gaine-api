<?php

declare(strict_types=1);

namespace App\Actions\Streamer;

use App\Actions\ActionResult;
use App\Data\Repository\StreamerRepositoryInterface;
use Exception;
use PDOException;

class StreamerListAction
{
    /** @var StreamerRepositoryInterface */
    private $streamerRepository;


    public function __construct(StreamerRepositoryInterface $streamerRepository)
    {
        $this->streamerRepository = $streamerRepository;
    }

    public function __invoke(): array
    {
        return $this->streamerRepository->getAll();
    }
}
