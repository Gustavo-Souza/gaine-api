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
use function PHPUnit\Framework\isEmpty;
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
        $streamersArray = $action->__invoke();

        // Assert
        assertThat(empty($streamersArray), equalTo(false));
    }

    public function testReturnEmpty(): void
    {
        // Arrange
        $streamers = [];

        $this->streamerRepository
            ->expects(once())
            ->method('getAll')
            ->willReturn($streamers);

        $action = new StreamerListAction($this->streamerRepository);

        // Act
        $streamersArray = $action->__invoke();

        // Assert
        assertThat(empty($streamersArray), equalTo(true));
    }
}
