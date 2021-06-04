<?php

declare(strict_types=1);

namespace Test\Unit\Actions\Code;

use App\Actions\Code\CodeSendAction;
use App\Data\Exception\ModelAlreadyExistsException;
use App\Data\PushNotification\PushNotificationInterface;
use App\Data\Repository\CodeRepositoryInterface;
use App\Data\Repository\UserRepositoryInterface;
use App\Exception\ValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class ActionSendCodeTest extends TestCase
{
    /** @var CodeRepositoryInterface|MockObject */
    private $codeRepository;

    /** @var UserRepositoryInterface|MockObject */
    private $userRepository;

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


    public function testSent(): void
    {
        // Arrange
        $usersDeviceId = ['1', '2', '3'];

        $this->codeRepository
            ->expects(once())
            ->method('create');
        $this->userRepository
            ->expects(once())
            ->method('getAllUsersDeviceIdWithNotificationEnabled')
            ->willReturn($usersDeviceId);
        $this->pushNotification
            ->expects(once())
            ->method('sendCode');

        $params = [
            'streamer_code' => 'UNKNW',
            'code' => 'ABCDE'
        ];

        $userId = 1;
        $action = new CodeSendAction(
            $this->codeRepository,
            $this->userRepository,
            $this->pushNotification
        );

        // Act
        $action->__invoke($userId, $params);

        // Assert
        assertTrue(true);
    }

    public function testFailedDueToCodeAlreadySent(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(once())
            ->method('create')
            ->willThrowException(new ModelAlreadyExistsException());

        $params = [
            'streamer_code' => 'UNKWN',
            'code' => 'ABCDE'
        ];

        $userId = 1;
        $action = new CodeSendAction(
            $this->codeRepository,
            $this->userRepository,
            $this->pushNotification
        );

        // Act
        $this->expectException(ModelAlreadyExistsException::class);

        $action->__invoke($userId, $params);
    }

    public function testFailedDueToMissingParams(): void
    {
        // Arrange
        $this->codeRepository
            ->expects(never())
            ->method('create');

        $params = [
            'code' => 'ABCDE'
        ];

        $userId = 1;
        $action = new CodeSendAction(
            $this->codeRepository,
            $this->userRepository,
            $this->pushNotification
        );

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($userId, $params);
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

        $userId = 1;
        $action = new CodeSendAction(
            $this->codeRepository,
            $this->userRepository,
            $this->pushNotification
        );

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($userId, $params);
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

        $userId = 1;
        $action = new CodeSendAction(
            $this->codeRepository,
            $this->userRepository,
            $this->pushNotification
        );

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($userId, $params);
    }
}
