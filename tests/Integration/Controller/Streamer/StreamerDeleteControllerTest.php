<?php

declare(strict_types=1);

namespace Test\Integration\Controller\Streamer;

use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Test\Integration\AppTestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class StreamerDeleteControllerTest extends AppTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        parent::cleanTables(['auth', 'users', 'streamers']);

        $_ENV['JWT_SECRET'] = 'secret';
    }


    public function testStreamerDeletedReturnsStatusCode201(): void
    {
        // Arrange
        $jwtToken = $this->authenticate();

        $params = [
            'streamer_code' => 'MYS',
            'streamer_name' => 'MyStreamer'
        ];
        $params_delete = [
            'streamer_code' => 'MYS'
        ];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $this->post('/streamers', $params, $headers);
        $response = $this->delete('/streamers', $params_delete, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_OK));
    }

    public function testReturnsStatusCode404WhenStreamerNotExists(): void
    {
        // Arrange
        $jwtToken = $this->authenticate();

        $params_delete = [
            'streamer_code' => 'MYS'
        ];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $response = $this->delete('/streamers', $params_delete, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_NOT_FOUND));
    }

    public function testReturnsStatusCode401WhenNotAuthenticated(): void
    {
        $params_delete = [
            'streamer_code' => 'MYS'
        ];

        // Act
        $response = $this->delete('/streamers', $params_delete);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_UNAUTHORIZED));
    }

    public function testReturnsStatusCode400WhenParamsAreInvalid(): void
    {
        // Arrange
        $jwtToken = $this->authenticate();

        $params = [
            'streamer_code' => 'MYS',
            'streamer_name' => 'MyStreamer'
        ];
        $params_delete = [
            'streamer_code' => 'M'
        ];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $this->post('/streamers', $params, $headers);
        $response = $this->delete('/streamers', $params_delete, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_BAD_REQUEST));
    }

    public function testReturnsStatusCode400WhenMissingParams(): void
    {
        // Arrange
        $jwtToken = $this->authenticate();

        $params = [
            'streamer_code' => 'MYS',
            'streamer_name' => 'MyStreamer'
        ];
        $params_delete = [''];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $this->post('/streamers', $params, $headers);
        $response = $this->delete('/streamers', $params_delete, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_BAD_REQUEST));
    }
}
