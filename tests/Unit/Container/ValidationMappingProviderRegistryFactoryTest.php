<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Container;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Container\ValidationMappingProviderRegistryFactory;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
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
        /** @var ValidationMappingProviderInterface $validationMappingProvider */
        $validationMappingProvider = $this->getMockByCalls(ValidationMappingProviderInterface::class, [
            Call::create('getClass')->with()->willReturn('class'),
        ]);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')
                ->with(ValidationMappingProviderInterface::class.'[]')
                ->willReturn([$validationMappingProvider]),
        ]);

        $factory = new ValidationMappingProviderRegistryFactory();

        $validationMappingProviderRegistry = $factory($container);

        self::assertInstanceOf(ValidationMappingProviderRegistryInterface::class, $validationMappingProviderRegistry);
    }
}
