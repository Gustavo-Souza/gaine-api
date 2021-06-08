<?php

declare(strict_types=1);

namespace Test\Integration\Controller\User;

use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Test\Integration\AppTestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertJson;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class UserAuthControllerTest extends AppTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        parent::cleanTables(['auth', 'users']);

        $_ENV['JWT_SECRET'] = 'secret';
    }


    public function testRegistrationReturnsStatusCode200AndToken(): void
    {
        // Arrange
        $params = [
            'firebase_authentication_id' => 'a',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => 'deviceId'
        ];

        // Act
        $response = $this->post('/users', $params);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_OK));
        assertJson($response->getBody()->__toString());

        $jsonArrayDecoded = json_decode($response->getBody()->__toString(), true);
        assertArrayHasKey('token', $jsonArrayDecoded);
    }

    public function testLoginReturnsStatusCode200AndToken(): void
    {
        // Arrange
        $paramsRegister = [
            'firebase_authentication_id' => 'a',
            'firebase_authentication_name' => 'Register',
            'firebase_cloud_messaging_device_id' => 'deviceId'
        ];
        $paramsLogin = [
            'firebase_authentication_id' => 'a',
            'firebase_authentication_name' => 'Login',
            'firebase_cloud_messaging_device_id' => 'deviceId'
        ];

        // Act
        $this->post('/users', $paramsRegister);
        $response = $this->post('/users', $paramsLogin);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_OK));
        assertJson($response->getBody()->__toString());

        $jsonArrayDecoded = json_decode($response->getBody()->__toString(), true);
        assertArrayHasKey('token', $jsonArrayDecoded);
    }

    public function testReturnsStatusCode400WhenParamsAreInvalid(): void
    {
        // Arrange
        $params = [
            'firebase_authentication_id' => 'a',
            'firebase_authentication_name' => '#Unknown',
            'firebase_cloud_messaging_device_id' => 'deviceId'
        ];

        // Act
        $response = $this->post('/users', $params);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_BAD_REQUEST));
    }

    public function testReturnsStatusCode400WhenMissingParams(): void
    {
        // Arrange
        $params = [
            'firebase_authentication_id' => 'a',
            'firebase_cloud_messaging_device_id' => 'deviceId'
        ];

        // Act
        $response = $this->post('/users', $params);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_BAD_REQUEST));
    }
}
