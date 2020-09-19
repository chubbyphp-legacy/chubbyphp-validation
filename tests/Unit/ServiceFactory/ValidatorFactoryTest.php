<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\ServiceFactory;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Chubbyphp\Validation\ServiceFactory\ValidatorFactory;
use Chubbyphp\Validation\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Chubbyphp\Validation\ServiceFactory\ValidatorFactory
 *
 * @internal
 */
final class ValidatorFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockByCalls(ValidationMappingProviderRegistryInterface::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('has')->with(ValidationMappingProviderRegistryInterface::class)->willReturn(true),
            Call::create('get')
                ->with(ValidationMappingProviderRegistryInterface::class)
                ->willReturn($validationMappingProviderRegistry),
            Call::create('has')->with(LoggerInterface::class)->willReturn(false),
        ]);

        $factory = new ValidatorFactory();

        $service = $factory($container);

        self::assertInstanceOf(ValidatorInterface::class, $service);
    }

    public function testCallStatic(): void
    {
        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockByCalls(ValidationMappingProviderRegistryInterface::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('has')->with(ValidationMappingProviderRegistryInterface::class.'default')->willReturn(true),
            Call::create('get')
                ->with(ValidationMappingProviderRegistryInterface::class.'default')
                ->willReturn($validationMappingProviderRegistry),
            Call::create('has')->with(LoggerInterface::class)->willReturn(false),
        ]);

        $factory = [ValidatorFactory::class, 'default'];

        $service = $factory($container);

        self::assertInstanceOf(ValidatorInterface::class, $service);
    }
}
