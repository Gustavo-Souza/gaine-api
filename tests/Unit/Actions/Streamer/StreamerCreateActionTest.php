<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Streamer;

use App\Actions\Streamer\StreamerCreateAction;
use App\Data\Exception\ModelAlreadyExistsException;
use App\Data\Repository\StreamerRepositoryInterface;
use App\Exception\ValidationException;
use Exception;
use PDOException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\assertTrue;
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
        $action->__invoke($params);

        // Assert
        assertTrue(true);
    }

    public function testFailedDueToStreamerAlreadyCreated(): void
    {
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
        $this->expectException(ModelAlreadyExistsException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToMissingParams(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('create');

        $params = [
            'streamer_name' => 'Unknown'
        ];
        $action = new StreamerCreateAction($this->streamerRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidStreamerCode(): void
    {
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
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidStreamerName(): void
    {
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
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }
}
