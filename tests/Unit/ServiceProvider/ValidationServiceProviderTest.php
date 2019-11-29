<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\ServiceProvider;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\ServiceProvider\ValidationServiceProvider;
use Chubbyphp\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * @covers \Chubbyphp\Validation\ServiceProvider\ValidationServiceProvider
 *
 * @internal
 */
final class ValidationServiceProviderTest extends TestCase
{
    public function testRegister(): void
    {
        $container = new Container();
        $container->register(new ValidationServiceProvider());

        self::assertTrue(isset($container['validator.mappings']));
        self::assertTrue(isset($container['validator.mappingproviderregistry']));
        self::assertTrue(isset($container['validator']));

        self::assertSame([], $container['validator.mappings']);
        self::assertInstanceOf(
            ValidationMappingProviderRegistry::class,
            $container['validator.mappingproviderregistry']
        );
        self::assertInstanceOf(Validator::class, $container['validator']);
    }
}
