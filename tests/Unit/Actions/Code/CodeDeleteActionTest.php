<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Code;

use App\Actions\Code\CodeDeleteAction;
use App\Actions\Code\CodeInvalidateAction;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;
use App\Data\Repository\CodeRepositoryInterface;
use App\Exception\ValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class CodeDeleteActionTest extends TestCase
{
    /** @var CodeRepositoryInterface|MockObject */
    private $codeRepository;


    protected function setUp(): void
    {
        $this->codeRepository = $this->createMock(
            CodeRepositoryInterface::class
        );
    }


    public function testDeleted(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(once())
            ->method('delete');

        $params = [
            'streamer_code' => 'UNKNW',
            'code' => 'ABCDE'
        ];

        $action = new CodeDeleteAction($this->codeRepository);

        // Act
        $action->__invoke($params);

        // Assert
        assertTrue(true);
    }

    public function testFailedDueToCodeNotExists(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(once())
            ->method('delete')
            ->willThrowException(new ModelNotFoundException());

        $params = [
            'streamer_code' => 'UNKNW',
            'code' => 'ABCDE'
        ];

        $action = new CodeDeleteAction($this->codeRepository);

        // Act
        $this->expectException(ModelNotFoundException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToMissingParams(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(never())
            ->method('delete');

        $params = [
            'code' => 'ABCDE'
        ];

        $action = new CodeDeleteAction($this->codeRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidStreamerCode(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(never())
            ->method('create');

        $params = [
            'streamer_code' => 'U',
            'code' => 'ABCDE'
        ];

        $action = new CodeDeleteAction($this->codeRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidCode(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(never())
            ->method('create');

        $params = [
            'streamer_code' => 'UNKWN',
            'code' => 'ABCDa'
        ];

        $action = new CodeDeleteAction($this->codeRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }
}
