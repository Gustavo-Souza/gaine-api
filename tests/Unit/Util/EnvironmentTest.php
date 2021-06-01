<?php

declare(strict_types=1);

namespace Test\Unit;

use App\Util\Environment;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\equalTo;

class EnvironmentTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV = [];
    }

    public function testHasValue(): void
    {
        // Arrange
        $_ENV['url'] = 'http://localhost';

        // Act
        $url = Environment::get('url');

        // Assert
        assertThat($url, equalTo('http://localhost'));
    }

    public function testHasNoValueWithNoKey(): void
    {
        // Arrange

        // Act
        $url = Environment::get('url');

        // Assert
        assertThat($url, equalTo(null));
    }

    public function testHasNoValueWhenNull(): void
    {
        // Arrange
        $_ENV['url'] = null;

        // Act
        $url = Environment::get('url');

        // Assert
        assertThat($url, equalTo(null));
    }

    public function testHasNoValueWhenEmpty(): void
    {
        // Arrange
        $_ENV['url'] = '';

        // Act
        $url = Environment::get('url');

        // Assert
        assertThat($url, equalTo(null));
    }
}
