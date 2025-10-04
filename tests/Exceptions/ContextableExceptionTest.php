<?php

namespace Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Sevaske\Support\Exceptions\ContextableException;

class ContextableExceptionTest extends TestCase
{
    public function testConstructorSetsMessageCodeAndContext(): void
    {
        $context = ['user_id' => 123];
        $exception = new ContextableException('Error occurred', $context, 404);

        // Test message
        $this->assertEquals('Error occurred', $exception->getMessage());

        // Test code
        $this->assertEquals(404, $exception->getCode());

        // Test context
        $this->assertEquals($context, $exception->context());
    }

    public function testWithContextAddsContext(): void
    {
        $exception = new ContextableException('Something went wrong', ['foo' => 'bar']);

        // Add new context
        $exception->withContext(['baz' => 42]);

        $expected = [
            'foo' => 'bar',
            'baz' => 42
        ];

        $this->assertEquals($expected, $exception->context());
    }

    public function testWithContextReturnsSelf(): void
    {
        $exception = new ContextableException();
        $result = $exception->withContext(['a' => 1]);

        $this->assertSame($exception, $result);
    }

    public function testContextInitiallyEmpty(): void
    {
        $exception = new ContextableException();

        $this->assertEquals([], $exception->context());
    }
}