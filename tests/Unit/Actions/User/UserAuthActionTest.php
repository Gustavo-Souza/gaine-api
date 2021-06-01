<?php

declare(strict_types=1);

namespace Test\Unit\Actions\User;

use App\Actions\User\UserAuthAction;
use App\Data\Exception\ModelNotFoundException;
use App\Data\Model\User;
use App\Data\Repository\AuthRepositoryInterface;
use App\Data\Repository\UserRepositoryInterface;
use App\Security\JwtToken;
use PDOException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\assertObjectHasAttribute;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;
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
        /*
            1. Returns a ModelNotFoundException when trying to find user if it
                was authenticated before.
            2. Save the user and returns an User instance.
            3. Generate the JWT token and save the authentication with the
                user id and JWT token.
            4. Returns an ActionResult with status Created (201) and
                token as JSON.
            5. Verify the JWT token.
        */

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
        $actionResult = $action->__invoke((object) $params);
        $json = $actionResult->getJson();
        $jsonArray = json_decode($json, true);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(201));
        assertJson($actionResult->getJson());
        assertArrayHasKey('token', $jsonArray);

        $result = JwtToken::decode($jsonArray['token'], $_ENV['JWT_SECRET']);
        assertObjectHasAttribute('exp', $result);
    }

    public function testLogin(): void
    {
        /*
            1. Returns an User when trying to find user if it
                was authenticated before.
            2. Update the user.
            3. Generate the JWT token and save the authentication with the
                user id and JWT token.
            4. Returns an ActionResult with status OK (200) and token as JSON.
            5. Verify the JWT token.
        */

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
        $actionResult = $action->__invoke((object) $params);
        $json = $actionResult->getJson();
        $jsonArray = json_decode($json, true);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(200));
        assertJson($actionResult->getJson());
        assertArrayHasKey('token', $jsonArray);

        $result = JwtToken::decode($jsonArray['token'], $_ENV['JWT_SECRET']);
        assertObjectHasAttribute('exp', $result);
    }

    public function testFailedDueToDatabaseError(): void
    {
        /*
            1. Returns an PDOException when trying to find user.
            2. Returns an ActionResult with status Internal Server Error (200).
        */

        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('findByFirebaseAuthenticationId')
            ->willThrowException(new PDOException());

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
        $actionResult = $action->__invoke((object) $params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(500));
        assertThat($actionResult->getJson(), equalTo(''));
    }

    public function testFailedDueToUnknownError(): void
    {
        /*
            1. Returns an unknown Exception when trying to find user.
            2. Returns an ActionResult with status Internal Server Error (200).
        */

        // Arrange
        $this->userRepository
            ->expects(once())
            ->method('findByFirebaseAuthenticationId')
            ->willThrowException(new PDOException());

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
        $actionResult = $action->__invoke((object) $params);

        // Assert
        assertThat($actionResult->getHttpStatusCode(), equalTo(500));
        assertThat($actionResult->getJson(), equalTo(''));
    }
}
