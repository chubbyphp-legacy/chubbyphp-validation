<?php

namespace Chubbyphp\Tests\Validation\Provider;

use Chubbyphp\Validation\Provider\ValidationProvider;
use Chubbyphp\Validation\Validator\ValidatorObjectMappingRegistry;
use Chubbyphp\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * @covers \Chubbyphp\Validation\Provider\ValidationProvider
 */
final class ValidationProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container();
        $container->register(new ValidationProvider());

        self::assertTrue(isset($container['validator.objectmappings']));
        self::assertTrue(isset($container['validator.objectmappingregistry']));
        self::assertTrue(isset($container['validator']));

        self::assertSame([], $container['validator.objectmappings']);
        self::assertInstanceOf(ValidatorObjectMappingRegistry::class, $container['validator.objectmappingregistry']);
        self::assertInstanceOf(Validator::class, $container['validator']);
    }
}
