<?php

declare(strict_types=1);

namespace Test\Integration\Controller\Streamer;

use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Test\Integration\AppTestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class StreamerListControllerTest extends AppTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        parent::cleanTables(['auth', 'users', 'streamers']);

        $_ENV['JWT_SECRET'] = 'secret';
    }


    public function testReturnsStatusCode200(): void
    {
        // Arrange
        $jwtToken = $this->authenticate();

        $params = [
            'streamer_code' => 'MYS',
            'streamer_name' => 'MyStreamer'
        ];
        $headers = ['Authorization' => 'Bearer ' . $jwtToken];

        // Act
        $this->post('/streamers', $params, $headers);
        $response = $this->get('/streamers', [], $headers);
        
        $json = $response->getBody()->__toString();
        $jsonArray = json_decode($json, true);
        $jsonArrayTotal = count(array_keys($jsonArray));

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_OK));
        assertThat($jsonArrayTotal, equalTo(1));
    }

    public function testReturnsStatusCode401WhenNotAuthenticated(): void
    {
        // Act
        $response = $this->get('/streamers');

        // Assert
        assertThat($response->getStatusCode(), equalTo(StatusCode::STATUS_UNAUTHORIZED));
    }
}
