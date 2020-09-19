<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\ServiceFactory;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Chubbyphp\Validation\ServiceFactory\ValidationMappingProviderRegistryFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Validation\Container\ValidationMappingProviderRegistryFactory
 *
 * @internal
 */
final class ValidationMappingProviderRegistryFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')->with(ValidationMappingProviderInterface::class.'[]')->willReturn([]),
        ]);

        $factory = new ValidationMappingProviderRegistryFactory();

        $service = $factory($container);

        self::assertInstanceOf(ValidationMappingProviderRegistryInterface::class, $service);
    }

    public function testCallStatic(): void
    {
        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')->with(ValidationMappingProviderInterface::class.'[]default')->willReturn([]),
        ]);

        $factory = [ValidationMappingProviderRegistryFactory::class, 'default'];

        $service = $factory($container);

        self::assertInstanceOf(ValidationMappingProviderRegistryInterface::class, $service);
    }
}
