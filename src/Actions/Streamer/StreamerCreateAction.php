<?php

declare(strict_types=1);

namespace App\Actions\Streamer;

use App\Actions\ActionResult;
use App\Actions\Streamer\Validation\StreamerCreateValidation;
use App\Data\Exception\ModelAlreadyExistsException;
use App\Data\Repository\StreamerRepositoryInterface;
use App\Exception\ValidationException;
use Exception;
use PDOException;
use stdClass;

class StreamerCreateAction
{
    /** @var StreamerRepositoryInterface */
    private $streamerRepository;


    public function __construct(StreamerRepositoryInterface $streamerRepository)
    {
        $this->streamerRepository = $streamerRepository;
    }

    public function __invoke(array $requestParams): ActionResult
    {
        try {
            $params = StreamerCreateValidation::validate($requestParams);
            return $this->createStreamer($params);
        } catch (ValidationException $exception) {
            return $this->badRequest($exception);
        } catch (ModelAlreadyExistsException $_) {
            return $this->streamerAlreadyExists();
        } catch (PDOException $exception) {
            return $this->error($exception);
        } catch (Exception $exception) {
            return $this->error($exception);
        }

        return new ActionResult();
    }


    private function createStreamer(stdClass $params): ActionResult
    {
        $this->streamerRepository->create(
            strtoupper($params->streamer_code),
            $params->streamer_name
        );
        return new ActionResult(201);
    }

    private function streamerAlreadyExists(): ActionResult
    {
        return new ActionResult(303);
    }

    private function badRequest(Exception $exception): ActionResult
    {
        return new ActionResult(400);
    }

    private function error(Exception $exception): ActionResult
    {
        // TODO: Log error
        return new ActionResult(500);
    }
}
