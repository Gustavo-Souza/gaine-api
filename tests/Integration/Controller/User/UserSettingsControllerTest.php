<?php

declare(strict_types=1);

namespace Test\Integration\Controller\User;

use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Test\Integration\AppTestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class UserSettingsControllerTest extends AppTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        parent::cleanTables(['auth', 'users']);

        $_ENV['JWT_SECRET'] = 'secret';
    }


    public function testNotificationToFalse(): void
    {
        // Arrange
        // ... 1 - Authenticate
        $paramsAuthentication = [
            'firebase_authentication_id' => 'auth',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => 'device'
        ];
        $responseAuthentication = $this->post('/users', $paramsAuthentication);

        // ... 2 - Get the jwt token from json
        $authenticationJson = $responseAuthentication->getBody()->__toString();
        $jsonArray = json_decode($authenticationJson, true);
        $jwtToken = $jsonArray['token'];

        $params = ['notification' => false];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $response = $this->patch('/users', $params, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_OK));
    }

    public function testNotificationToTrue(): void
    {
        // Arrange
        // ... 1 - Authenticate
        $paramsAuthentication = [
            'firebase_authentication_id' => 'auth',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => 'device'
        ];
        $responseAuthentication = $this->post('/users', $paramsAuthentication);

        // ... 2 - Get the jwt token from json
        $authenticationJson = $responseAuthentication->getBody()->__toString();
        $jsonArray = json_decode($authenticationJson, true);
        $jwtToken = $jsonArray['token'];

        $params = ['notification' => true];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $response = $this->patch('/users', $params, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_OK));
    }

    public function testReturnsStatusCode401WhenNotAuthenticated(): void
    {
        // Arrange
        $params = ['notification' => 'a'];
        $headers = ['Authorization' => 'Beare ' . 'unknownToken'];

        // Act
        $response = $this->patch('/users', $params, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_UNAUTHORIZED));
    }

    public function testReturnsStatusCode400WhenParamsAreInvalid(): void
    {
        // Arrange
        // ... 1 - Authenticate
        $paramsAuthentication = [
            'firebase_authentication_id' => 'auth',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => 'device'
        ];
        $responseAuthentication = $this->post('/users', $paramsAuthentication);

        // ... 2 - Get the jwt token from json
        $authenticationJson = $responseAuthentication->getBody()->__toString();
        $jsonArray = json_decode($authenticationJson, true);
        $jwtToken = $jsonArray['token'];

        $params = ['notification' => 'a'];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $response = $this->patch('/users', $params, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_BAD_REQUEST));
    }

    public function testReturnsStatusCode400WhenMissingParams(): void
    {
        // Arrange
        // ... 1 - Authenticate
        $paramsAuthentication = [
            'firebase_authentication_id' => 'auth',
            'firebase_authentication_name' => 'Unknown',
            'firebase_cloud_messaging_device_id' => 'device'
        ];
        $responseAuthentication = $this->post('/users', $paramsAuthentication);

        // ... 2 - Get the jwt token from json
        $authenticationJson = $responseAuthentication->getBody()->__toString();
        $jsonArray = json_decode($authenticationJson, true);
        $jwtToken = $jsonArray['token'];

        $params = [];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $response = $this->patch('/users', $params, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_BAD_REQUEST));
    }
}
