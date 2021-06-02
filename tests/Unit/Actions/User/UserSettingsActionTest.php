<?php

declare(strict_types=1);

namespace Test\Unit\Actions\User;

use App\Actions\User\UserSettingsAction;
use App\Data\Repository\UserRepositoryInterface;
use PDOException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertThat;
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
        /*
            1. Set notification enabled based on value in params.
            2. Returns an ActionResult with status OK (200).
        */

        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('setNotificationEnabled');

        $params = [
            'notification' => false
        ];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $actionResult = $action->__invoke(0, $params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(200));
    }

    public function testSetNotificationToTrue(): void
    {
        /*
            1. Set notification enabled based on value in params.
            2. Returns an ActionResult with status OK (200).
        */

        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('setNotificationEnabled');

        $params = [
            'notification' => true
        ];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $actionResult = $action->__invoke(0, $params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(200));
    }

    public function testFailedDueToMissingParams(): void
    {
        /*
            1. Launch a ValidationException when validating params.
            2. Returns an ActionResult with status Bad Request (400).
        */

        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('setNotificationEnabled');

        $params = [];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $actionResult = $action->__invoke(0, $params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(400));
    }

    public function testFailedDueToInvalidNotificationBooleanValue(): void
    {
        /*
            1. Launch a ValidationException when validating params.
            2. Returns an ActionResult with status Bad Request (400).
        */

        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('setNotificationEnabled');

        $params = [
            'notification' => 'a'
        ];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $actionResult = $action->__invoke(0, $params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(400));
    }

    public function testFailedDueToDatabaseError(): void
    {
        /*
            1. Launch an PDOException when validating params.
            2. Returns an ActionResult with status Internal Server Error (500).
        */

        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('setNotificationEnabled')
            ->willThrowException(new PDOException());

        $params = [
            'notification' => true
        ];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $actionResult = $action->__invoke(0, $params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(500));
    }

    public function testFailedDueToUnknownError(): void
    {
        /*
            1. Launch an Exception when validating params.
            2. Returns an ActionResult with status Internal Server Error (500).
        */

        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('setNotificationEnabled');

        $params = [
            'notification' => 'a'
        ];
        $action = new UserSettingsAction($this->userRepository);

        // Act
        $actionResult = $action->__invoke(0, $params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(400));
    }
}
