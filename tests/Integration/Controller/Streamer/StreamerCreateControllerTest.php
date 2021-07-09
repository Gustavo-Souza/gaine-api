<?php

declare(strict_types=1);

namespace Test\Integration\Controller\Streamer;

use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Test\Integration\AppTestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class StreamerCreateControllerTest extends AppTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        parent::cleanTables(['auth', 'users', 'streamers']);

        $_ENV['JWT_SECRET'] = 'secret';
    }


    public function testStreamerCreatedReturnsStatusCode201(): void
    {
        // Arrange
        $jwtToken = $this->authenticate();

        $params = [
            'streamer_code' => 'MYS',
            'streamer_name' => 'MyStreamer'
        ];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $response = $this->post('/streamers', $params, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_CREATED));
    }

    public function testReturnsStatusCode401WhenNotAuthenticated(): void
    {
        // Arrange
        $params = [
            'streamer_code' => 'MYS',
            'streamer_name' => 'MyStreamer'
        ];

        // Act
        $response = $this->post('/streamers', $params);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_UNAUTHORIZED));
    }

    public function testReturnsStatusCode400WhenParamsAreInvalid(): void
    {
        // Arrange
        $jwtToken = $this->authenticate();

        $params = [
            'streamer_code' => 'S',
            'streamer_name' => 'MyStreamer'
        ];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $response = $this->post('/streamers', $params, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_BAD_REQUEST));
    }

    public function testReturnsStatusCode400WhenMissingParams(): void
    {
        // Arrange
        $jwtToken = $this->authenticate();

        $params = [
            'streamer_name' => 'MyStreamer'
        ];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $response = $this->post('/streamers', $params, $headers);

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_BAD_REQUEST));
    }
}
