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

    public function __invoke(): ActionResult
    {
        try {
            return $this->getAllStreamers();
        } catch (PDOException $exception) {
            return $this->error($exception);
        } catch (Exception $exception) {
            return $this->error($exception);
        }

        return new ActionResult(200);
    }


    private function getAllStreamers(): ActionResult
    {
        $streamers = $this->streamerRepository->getAll();
        $json = json_encode($streamers);

        return new ActionResult(200, $json);
    }


    private function error(Exception $exception): ActionResult
    {
        // TODO: Log error
        return new ActionResult(500);
    }
}
