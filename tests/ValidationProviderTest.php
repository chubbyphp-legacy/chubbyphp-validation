<?php

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\ValidationProvider;
use Chubbyphp\Validation\Validator;
use Pimple\Container;

/**
 * @covers Chubbyphp\Validation\ValidationProvider
 */
final class ValidationProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $container = new Container();
        $container->register(new ValidationProvider());

        self::assertTrue(isset($container['validator.repositories']));
        self::assertTrue(isset($container['validator']));

        self::assertSame([], $container['validator.repositories']);
        self::assertInstanceOf(Validator::class, $container['validator']);
    }
}
