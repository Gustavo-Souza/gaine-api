<?php

declare(strict_types=1);

namespace Test\Unit\Actions\User;

use App\Actions\User\UserAuthAction;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;
use App\Data\Repository\AuthRepositoryInterface;
use App\Data\Repository\UserRepositoryInterface;
use App\Exception\ValidationException;
use App\Security\JwtToken;
use PDOException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\assertObjectHasAttribute;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;
use function PHPUnit\Framework\never;
use function PHPUnit\Framework\once;

class UserAuthActionTest extends TestCase
{
    /** @var UserRepositoryInterface|MockObject */
    private $userRepository;

    /** @var AuthRepositoryInterface|MockObject */
    private $authRepository;


    protected function setUp(): void
    {
        $_ENV['JWT_SECRET'] = 'secret';

        $this->userRepository = $this->createMock(
            UserRepositoryInterface::class
        );
        $this->authRepository = $this->createMock(
            AuthRepositoryInterface::class
        );
    }


    public function testRegistration(): void
    {
        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('findByFirebaseAuthenticationId')
            ->willThrowException(new ModelNotFoundException());
        $this->userRepository
            ->expects(once())
            ->method('create')
            ->willReturn(new User());
        $this->authRepository
            ->expects(once())
            ->method('save');

        $params = [
            'firebase_authentication_id' => 'a',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => 'a'
        ];
        $action = new UserAuthAction(
            $this->userRepository,
            $this->authRepository
        );

        // Act
        $jsonArray = $action->__invoke($params);

        // Assert
        assertArrayHasKey('token', $jsonArray);

        $jsonArrayDecoded =
            JwtToken::decode($jsonArray['token'], $_ENV['JWT_SECRET']);
        assertObjectHasAttribute('exp', $jsonArrayDecoded);
    }

    public function testLogin(): void
    {
        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('findByFirebaseAuthenticationId')
            ->willReturn(new User());
        $this->userRepository
            ->expects(once())
            ->method('update');
        $this->authRepository
            ->expects(once())
            ->method('save');

        $params = [
            'firebase_authentication_id' => 'a',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => 'a'
        ];
        $action = new UserAuthAction(
            $this->userRepository,
            $this->authRepository
        );

        // Act
        $jsonArray = $action->__invoke($params);

        // Assert
        assertArrayHasKey('token', $jsonArray);

        $jsonArrayDecoded =
            JwtToken::decode($jsonArray['token'], $_ENV['JWT_SECRET']);
        assertObjectHasAttribute('exp', $jsonArrayDecoded);
    }

    public function testFailedDueToMissingParams(): void
    {
        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('findByFirebaseAuthenticationId');

        $params = [
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => '#a'
        ];
        $action = new UserAuthAction(
            $this->userRepository,
            $this->authRepository
        );

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidFirebaseAuthenticationId(): void
    {
        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('findByFirebaseAuthenticationId');

        $params = [
            'firebase_authentication_id' => '!a',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => 'a'
        ];
        $action = new UserAuthAction(
            $this->userRepository,
            $this->authRepository
        );

        // Act
        $this->expectException(ValidationException::class);

        $action->__invoke($params);
    }

    public function testFailedDueToInvalidFirebaseAuthenticationName(): void
    {
        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('findByFirebaseAuthenticationId');

        $params = [
            'firebase_authentication_id' => 'a',
            'firebase_authentication_name' => '@Unknown',
            'firebase_cloud_messaging_device_id' => 'a'
        ];
        $action = new UserAuthAction(
            $this->userRepository,
            $this->authRepository
        );

        // Act
        $this->expectException(ValidationException::class);

        $actionResult = $action->__invoke($params);
    }

    public function testFailedDueToInvalidFirebaseCloudMessagingDeviceId(): void
    {
        // Arrange
        $this->userRepository
            ->expects(never())
            ->method('findByFirebaseAuthenticationId');

        $params = [
            'firebase_authentication_id' => 'a',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => '#a'
        ];
        $action = new UserAuthAction(
            $this->userRepository,
            $this->authRepository
        );

        // Act
        $this->expectException(ValidationException::class);

        $actionResult = $action->__invoke($params);
    }
}
