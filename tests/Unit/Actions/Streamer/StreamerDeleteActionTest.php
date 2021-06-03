<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Streamer;

use App\Actions\Streamer\StreamerDeleteAction;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Repository\StreamerRepositoryInterface;
use App\Exception\ValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class StreamerDeleteActionTest extends TestCase
{
    /** @var StreamerRepositoryInterface|MockObject */
    private $streamerRepository;


    protected function setUp(): void
    {
        $this->streamerRepository = $this->createMock(
            StreamerRepositoryInterface::class
        );
    }


    public function testDeleted(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(once())
            ->method('delete');

        $params = [
            'streamer_code' => 'UNKNW'
        ];
        $action = new StreamerDeleteAction($this->streamerRepository);

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
            ->method('delete')
            ->willThrowException(new ModelNotFoundException());

        $params = [
            'streamer_code' => 'UNKNW'
        ];
        $action = new StreamerDeleteAction($this->streamerRepository);

        // Act
        $this->expectException(ModelNotFoundException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToMissingParams(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('delete');

        $params = [];
        $action = new StreamerDeleteAction($this->streamerRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidStreamerCode(): void
    {
        // Arrange
        $this->streamerRepository
            ->expects(never())
            ->method('delete');

        $params = [
            'streamer_code' => 'U'
        ];
        $action = new StreamerDeleteAction($this->streamerRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }
}
