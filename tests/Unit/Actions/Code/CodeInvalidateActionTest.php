<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Code;

use App\Actions\Code\CodeInvalidateAction;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;
use App\Data\PushNotification\PushNotificationInterface;
use App\Data\Repository\CodeRepositoryInterface;
use App\Data\Repository\UserRepositoryInterface;
use App\Exception\ValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class CodeInvalidateActionTest extends TestCase
{
    /** @var CodeRepositoryInterface|MockObject */
    private $codeRepository;

    /** @var PushNotificationInterface|MockObject */
    private $pushNotification;


    protected function setUp(): void
    {
        $this->codeRepository = $this->createMock(
            CodeRepositoryInterface::class
        );
        $this->userRepository = $this->createMock(
            UserRepositoryInterface::class
        );
        $this->pushNotification = $this->createMock(
            PushNotificationInterface::class
        );
    }


    public function testInvalidate(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(once())
            ->method('get');
        $this->codeRepository
            ->expects(once())
            ->method('getUserFromCodeSent');
        $this->codeRepository
            ->expects(once())
            ->method('setCodeInvalidated');
        $this->pushNotification
            ->expects(once())
            ->method('sendCodeInvalidationWarningForUser');

        $params = [
            'streamer_code' => 'UNKNW',
            'code' => 'ABCDE'
        ];

        $user = new User();
        $action = new CodeInvalidateAction(
            $this->codeRepository,
            $this->pushNotification
        );

        // Act
        $action->__invoke($user, $params);

        // Assert
        assertTrue(true);
    }

    public function testFailedDueToCodeNotExists(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(once())
            ->method('get')
            ->willThrowException(new ModelNotFoundException());

        $params = [
            'streamer_code' => 'UNKNW',
            'code' => 'ABCDE'
        ];

        $user = new User();
        $action = new CodeInvalidateAction(
            $this->codeRepository,
            $this->pushNotification
        );

        // Act
        $this->expectException(ModelNotFoundException::class);

        $action->__invoke($user, $params);
    }

    public function testFailedDueToMissingParams(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(never())
            ->method('get');

        $params = [
            'code' => 'ABCDE'
        ];

        $user = new User();
        $action = new CodeInvalidateAction(
            $this->codeRepository,
            $this->pushNotification
        );

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($user, $params);
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

        $user = new User();
        $action = new CodeInvalidateAction(
            $this->codeRepository,
            $this->pushNotification
        );

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($user, $params);
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

        $user = new User();
        $action = new CodeInvalidateAction(
            $this->codeRepository,
            $this->pushNotification
        );

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($user, $params);
    }
}
