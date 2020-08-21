<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Container;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Container\ValidatorFactory;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Chubbyphp\Validation\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Chubbyphp\Validation\Container\ValidatorFactory
 *
 * @internal
 */
final class ValidatorFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var ContainerInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockByCalls(ValidationMappingProviderRegistryInterface::class);

        /** @var LoggerInterface $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('get')
                ->with(ValidationMappingProviderRegistryInterface::class)
                ->willReturn($validationMappingProviderRegistry),
            Call::create('get')
                ->with(LoggerInterface::class)
                ->willReturn($logger),
        ]);

        $factory = new ValidatorFactory();

        $validator = $factory($container);

        self::assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
