<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\ServiceFactory;

use Chubbyphp\Container\Container;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\ServiceFactory\ValidationServiceFactory;
use Chubbyphp\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\ServiceFactory\ValidationServiceFactory
 *
 * @internal
 */
final class ValidationServiceFactoryTest extends TestCase
{
    public function testRegister(): void
    {
        $container = new Container();
        $container->factories((new ValidationServiceFactory())());

        self::assertTrue($container->has('validator.mappings'));
        self::assertTrue($container->has('validator.mappingproviderregistry'));
        self::assertTrue($container->has('validator'));

        self::assertSame([], $container->get('validator.mappings'));
        self::assertInstanceOf(
            ValidationMappingProviderRegistry::class,
            $container->get('validator.mappingproviderregistry')
        );
        self::assertInstanceOf(Validator::class, $container->get('validator'));
    }
}
