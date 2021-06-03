<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Streamer;

use App\Actions\Streamer\StreamerUpdateAction;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Repository\StreamerRepositoryInterface;
use App\Exception\ValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class StreamerUpdateActionTest extends TestCase
{
    /** @var StreamerRepositoryInterface|MockObject */
    private $streamerRepository;


    protected function setUp(): void
    {
        $this->streamerRepository = $this->createMock(
            StreamerRepositoryInterface::class
        );
    }


    public function testUpdated(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('update');

        $params = [
            'streamer_code' => 'UNKNW',
            'streamer_code_updated' => 'UNK',
            'streamer_name_updated' => 'Unknown'
        ];
        $action = new StreamerUpdateAction($this->streamerRepository);

        // Act
        $action->__invoke($params);

        // Assert
        assertTrue(true);
    }

    public function testFailedDueToStreamerNotExists(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('update')
            ->willThrowException(new ModelNotFoundException());

        $params = [
            'streamer_code' => 'UNKNW',
            'streamer_code_updated' => 'UNK',
            'streamer_name_updated' => 'Unknown'
        ];
        $action = new StreamerUpdateAction($this->streamerRepository);

        // Act
        $this->expectException(ModelNotFoundException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToMissingParams(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('update');

        $params = [
            'streamer_code' => 'UNKNW',
            'streamer_name_updated' => 'Unknown'
        ];
        $action = new StreamerUpdateAction($this->streamerRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidStreamerCode(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('update');

        $params = [
            'streamer_code' => 'U',
            'streamer_code_updated' => 'UNK',
            'streamer_name_updated' => 'Unknown'
        ];
        $action = new StreamerUpdateAction($this->streamerRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidStreamerCodeUpdated(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('update');

        $params = [
            'streamer_code' => 'UNKW',
            'streamer_code_updated' => 'U',
            'streamer_name_updated' => 'Unknown'
        ];
        $action = new StreamerUpdateAction($this->streamerRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidStreamerNameUpdated(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('update');

        $params = [
            'streamer_code' => 'U',
            'streamer_code_updated' => 'UNK',
            'streamer_name_updated' => 'Unk#own'
        ];
        $action = new StreamerUpdateAction($this->streamerRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }
}
