<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Streamer;

use App\Actions\Streamer\StreamerListAction;
use App\Data\Repository\StreamerRepositoryInterface;
use Exception;
use PDOException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\once;

class StreamerListActionTest extends TestCase
{
    /** @var StreamerRepositoryInterface|MockObject */
    private $streamerRepository;


    protected function setUp(): void
    {
        $this->streamerRepository = $this->createMock(
            StreamerRepositoryInterface::class
        );
    }


    public function testReturnStreamers(): void
    {
        /*
            1. Get all streamers by StreamerRepository.
            2. Returns an ActionResult with http status code OK (200).
        */

        // Arrange
        $streamers = [
            ['streamer_code' => 'UNKNW', 'streamer_name' => 'Unknown'],
            ['streamer_code' => 'TEST', 'streamer_name' => 'Test']
        ];

        $this->streamerRepository
            ->expects(once())
            ->method('getAll')
            ->willReturn($streamers);

        $action = new StreamerListAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke();

        // Assert
        $expectedJson = json_encode($streamers);

        assertThat($actionResult->getHttpStatusCode(), equalTo(200));
        assertThat($actionResult->getJson(), equalTo($expectedJson));
    }

    public function testReturnEmpty(): void
    {
        /*
            1. Get all streamers by StreamerRepository.
            2. Returns an ActionResult with http status code OK (200).
        */

        // Arrange
        $streamers = [];

        $this->streamerRepository
            ->expects(once())
            ->method('getAll')
            ->willReturn($streamers);

        $action = new StreamerListAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke();

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(200));
        assertThat($actionResult->getJson(), equalTo('[]'));
    }

    public function testFailedDueToDatabaseError(): void
    {
        /*
            1. Launch a PDOException when getting all streamers.
            2. Returns an ActionResult with http status code
                Internal Server Error(200).
        */

        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('getAll')
            ->willThrowException(new PDOException());

        $action = new StreamerListAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke();

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(500));
    }

    public function testFailedDueToUnknownError(): void
    {
        /*
            1. Launch a PDOException when getting all streamers.
            2. Returns an ActionResult with http status code
                Internal Server Error(200).
        */

        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('getAll')
            ->willThrowException(new Exception());

        $action = new StreamerListAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke();

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(500));
    }
}
