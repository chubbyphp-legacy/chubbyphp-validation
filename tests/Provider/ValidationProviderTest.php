<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Provider;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\Provider\ValidationProvider;
use Chubbyphp\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * @covers \Chubbyphp\Validation\Provider\ValidationProvider
 *
 * @internal
 */
final class ValidationProviderTest extends TestCase
{
    public function testRegister(): void
    {
        $container = new Container();
        $container->register(new ValidationProvider());

        self::assertTrue(isset($container['validator.mappings']));
        self::assertTrue(isset($container['validator.mappingproviderregistry']));
        self::assertTrue(isset($container['validator']));

        self::assertSame([], $container['validator.mappings']);
        self::assertInstanceOf(ValidationMappingProviderRegistry::class, $container['validator.mappingproviderregistry']);
        self::assertInstanceOf(Validator::class, $container['validator']);
    }
}
