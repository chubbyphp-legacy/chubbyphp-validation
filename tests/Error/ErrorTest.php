<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Error;

use Chubbyphp\Validation\Error\Error;

/**
 * @covers \Chubbyphp\Validation\Error\Error
 */
final class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutArguments()
    {
        $path = 'path';
        $key = 'key';

        $error = new Error($path, $key);

        self::assertSame($path, $error->getPath());
        self::assertSame($key, $error->getKey());
        self::assertSame([], $error->getArguments());
    }

    public function testWithArguments()
    {
        $path = 'path';
        $key = 'key';
        $arguments = ['input' => 'input'];

        $error = new Error($path, $key, $arguments);

        self::assertSame($path, $error->getPath());
        self::assertSame($key, $error->getKey());
        self::assertSame($arguments, $error->getArguments());
    }
}
