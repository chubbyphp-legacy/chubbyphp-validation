<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\LazyValidationMappingProvider;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Validation\Mapping\LazyValidationMappingProvider
 */
class LazyValidationMappingProviderTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testInvoke()
    {
        $denormalizationClassMapping = $this->getValidationClassMapping();
        $denormalizationPropertyMappings = [$this->getValidationPropertyMapping()];

        $container = $this->getContainer([
            'service' => $this->getValidationObjectMapping(
                $denormalizationClassMapping,
                $denormalizationPropertyMappings
            ),
        ]);

        $objectMapping = new LazyValidationMappingProvider($container, 'service', \stdClass::class);

        self::assertEquals(\stdClass::class, $objectMapping->getClass());
        self::assertSame($denormalizationClassMapping, $objectMapping->getValidationClassMapping('path'));
        self::assertSame($denormalizationPropertyMappings, $objectMapping->getValidationPropertyMappings('path'));
    }

    /**
     * @param array $services
     *
     * @return ContainerInterface
     */
    private function getContainer(array $services): ContainerInterface
    {
        /** @var ContainerInterface|MockObject $container */
        $container = $this->getMockBuilder(ContainerInterface::class)->setMethods(['get'])->getMockForAbstractClass();

        $container
            ->expects(self::any())
            ->method('get')
            ->willReturnCallback(function (string $id) use ($services) {
                return $services[$id];
            })
        ;

        return $container;
    }

    /**
     * @param ValidationClassMappingInterface|null $denormalizationClassMapping
     * @param ValidationPropertyMappingInterface[] $denormalizationPropertyMappings
     *
     * @return ValidationMappingProviderInterface
     */
    private function getValidationObjectMapping(
        ValidationClassMappingInterface $denormalizationClassMapping = null,
        array $denormalizationPropertyMappings
    ): ValidationMappingProviderInterface {
        /** @var ValidationMappingProviderInterface|MockObject $mapping */
        $mapping = $this->getMockForInterface(ValidationMappingProviderInterface::class, [
            'getValidationClassMapping' => [
                ['arguments' => ['path'], 'return' => $denormalizationClassMapping],
            ],
            'getValidationPropertyMappings' => [
                ['arguments' => ['path'], 'return' => $denormalizationPropertyMappings],
            ],
        ]);

        return $mapping;
    }

    /**
     * @return ValidationClassMappingInterface|MockObject
     */
    private function getValidationClassMapping(): ValidationClassMappingInterface
    {
        return $this->getMockForInterface(ValidationClassMappingInterface::class);
    }

    /**
     * @return ValidationPropertyMappingInterface|MockObject
     */
    private function getValidationPropertyMapping(): ValidationPropertyMappingInterface
    {
        return $this->getMockForInterface(ValidationPropertyMappingInterface::class);
    }
}
