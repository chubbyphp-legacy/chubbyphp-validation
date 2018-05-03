<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationObjectMappingInterface;
use Chubbyphp\Validation\Mapping\LazyValidationObjectMapping;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Validation\Mapping\LazyValidationObjectMapping
 */
class LazyValidationObjectMappingTest extends TestCase
{
    public function testInvoke()
    {
        $denormalizationClassMapping = $this->getValidationClassMapping();
        $denormalizationPropertyMappings = [$this->getValidationPropertyMapping()];

        $factory = function () {
        };

        $container = $this->getContainer([
            'service' => $this->getValidationObjectMapping(
                $denormalizationClassMapping,
                $denormalizationPropertyMappings
            ),
        ]);

        $objectMapping = new LazyValidationObjectMapping($container, 'service', \stdClass::class);

        self::assertEquals(\stdClass::class, $objectMapping->getClass());
        self::assertSame($denormalizationPropertyMappings, $objectMapping->getValidationPropertyMappings('path'));
    }

    /**
     * @param array $services
     *
     * @return ContainerInterface
     */
    private function getContainer(array $services): ContainerInterface
    {
        /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
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
     * @return ValidationObjectMappingInterface
     */
    private function getValidationObjectMapping(
        ValidationClassMappingInterface $denormalizationClassMapping = null,
        array $denormalizationPropertyMappings
    ): ValidationObjectMappingInterface {
        /** @var ValidationObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this
            ->getMockBuilder(ValidationObjectMappingInterface::class)
            ->setMethods(['getValidationClassMapping', 'getValidationPropertyMappings'])
            ->getMockForAbstractClass()
        ;

        $mapping->expects(self::any())
            ->method('getValidationClassMapping')
            ->with(self::equalTo('path'))
            ->willReturn($denormalizationClassMapping);

        $mapping->expects(self::any())
            ->method('getValidationPropertyMappings')
            ->with(self::equalTo('path'))
            ->willReturn($denormalizationPropertyMappings);

        return $mapping;
    }

    /**
     * @return ValidationClassMappingInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getValidationClassMapping(): ValidationClassMappingInterface
    {
        return $this->getMockBuilder(ValidationClassMappingInterface::class)->getMockForAbstractClass();
    }

    /**
     * @return ValidationPropertyMappingInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getValidationPropertyMapping(): ValidationPropertyMappingInterface
    {
        return $this->getMockBuilder(ValidationPropertyMappingInterface::class)->getMockForAbstractClass();
    }
}
