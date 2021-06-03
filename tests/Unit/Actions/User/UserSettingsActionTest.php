<?php

declare(strict_types=1);

namespace Test\Unit\Actions\User;

use App\Actions\User\UserSettingsAction;
use App\Data\Repository\UserRepositoryInterface;
use App\Exception\ValidationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class UserSettingsActionTest extends TestCase
{
    /** @var UserRepositoryInterface|MockObject */
    private $userRepository;


    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(
            UserRepositoryInterface::class
        );
    }


    public function testSetNotificationToFalse(): void
    {
        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('setNotificationEnabled');

        $params = [
            'notification' => false
        ];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $action->__invoke(0, $params);

        // Assert
        assertTrue(true);
    }

    public function testSetNotificationToTrue(): void
    {
        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('setNotificationEnabled');

        $params = [
            'notification' => true
        ];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $action->__invoke(0, $params);

        // Assert
        assertTrue(true);
    }

    public function testFailedDueToMissingParams(): void
    {
        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('setNotificationEnabled');

        $params = [];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke(0, $params);
    }

    public function testFailedDueToInvalidNotificationBooleanValue(): void
    {
        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('setNotificationEnabled');

        $params = [
            'notification' => 'a'
        ];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke(0, $params);
    }
}
