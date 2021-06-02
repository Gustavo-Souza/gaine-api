<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Streamer;

use App\Actions\Streamer\StreamerCreateAction;
use App\Data\Exception\ModelAlreadyExistsException;
use App\Data\Repository\StreamerRepositoryInterface;
use Exception;
use PDOException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class StreamerCreateActionTest extends TestCase
{
    /** @var StreamerRepositoryInterface|MockObject */
    private $streamerRepository;


    protected function setUp(): void
    {
        $this->streamerRepository = $this->createMock(
            StreamerRepositoryInterface::class
        );
    }


    public function testCreated(): void
    {
        /*
            1. Save the streamer.
            2. Returns an ActionResult with status Created (201).
        */

        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('create');

        $params = [
            'streamer_code' => 'UNKNW',
            'streamer_name' => 'Unknown'
        ];
        $action = new StreamerCreateAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke($params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(201));
    }

    public function testFailedDueToStreamerAlreadyCreated(): void
    {
        /*
            1. Save the streamer.
            2. Returns an ActionResult with status See Other (303).
        */

        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('create')
            ->willThrowException(new ModelAlreadyExistsException());

        $params = [
            'streamer_code' => 'UNKW',
            'streamer_name' => 'Unknown'
        ];
        $action = new StreamerCreateAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke($params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(303));
    }

    public function testFailedDueToMissingParams(): void
    {
        /*
            1. Save the streamer.
            2. Returns an ActionResult with status Bad Request (400).
        */

        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('create');

        $params = [
            'streamer_name' => 'Unknown'
        ];
        $action = new StreamerCreateAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke($params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(400));
    }

    public function testFailedDueToInvalidStreamerCode(): void
    {
        /*
            1. Save the streamer.
            2. Returns an ActionResult with status Bad Request (400).
        */

        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('create');

        $params = [
            'streamer_code' => 'UNKNOWN',
            'streamer_name' => 'Unknown'
        ];
        $action = new StreamerCreateAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke($params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(400));
    }

    public function testFailedDueToInvalidStreamerName(): void
    {
        /*
            1. Save the streamer.
            2. Returns an ActionResult with status Bad Request (400).
        */

        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('create');

        $params = [
            'streamer_code' => 'UNKN',
            'streamer_name' => '@Unknown'
        ];
        $action = new StreamerCreateAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke($params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(400));
    }

    public function testFailedDueToDatabaseError(): void
    {
        /*
            1. Launch a PDOException when getting all streamers.
            2. Returns an ActionResult with http status code
                Internal Server Error (500).
        */

        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('create')
            ->willThrowException(new PDOException());

        $params = [
            'streamer_code' => 'UNKNW',
            'streamer_name' => 'Unknown'
        ];
        $action = new StreamerCreateAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke($params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(500));
    }

    public function testFailedDueToUnknownError(): void
    {
        /*
            1. Launch a PDOException when creating streamer.
            2. Returns an ActionResult with http status code
                Internal Server Error (500).
        */

        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('create')
            ->willThrowException(new Exception());

        $params = [
            'streamer_code' => 'UNKNW',
            'streamer_name' => 'Unknown'
        ];
        $action = new StreamerCreateAction($this->streamerRepository);

        // Act
        $actionResult = $action->__invoke($params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(500));
    }
}
