<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Validation\Mapping\PropertyMappingInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\LazyObjectMapping;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Interop\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Validation\Mapping\LazyObjectMapping
 */
final class LazyObjectMappingTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        $constraints = [$this->getConstraint()];
        $propertyMappings = [$this->getPropertyMapping('name', [$this->getConstraint()])];

        $container = $this->getContainer([
            'service' => $this->getObjectMapping('class', $constraints, $propertyMappings),
        ]);

        $objectMapping = new LazyObjectMapping($container, 'service', 'class');

        self::assertSame('class', $objectMapping->getClass());
        self::assertSame($constraints, $objectMapping->getConstraints());
        self::assertSame($propertyMappings, $objectMapping->getPropertyMappings());
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
     * @param string $class
     * @param array  $constraints
     * @param array  $propertyMappings
     *
     * @return ObjectMappingInterface
     */
    private function getObjectMapping(
        string $class,
        array $constraints,
        array $propertyMappings
    ): ObjectMappingInterface {
        /** @var ObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this
            ->getMockBuilder(ObjectMappingInterface::class)
            ->setMethods(['getClass', 'getConstraints', 'getPropertyMappings'])
            ->getMockForAbstractClass()
        ;

        $mapping->expects(self::any())->method('getClass')->willReturn($class);
        $mapping->expects(self::any())->method('getConstraints')->willReturn($constraints);
        $mapping->expects(self::any())->method('getPropertyMappings')->willReturn($propertyMappings);

        return $mapping;
    }

    /**
     * @param string $name
     * @param array  $constraints
     *
     * @return PropertyMappingInterface
     */
    private function getPropertyMapping(string $name, array $constraints): PropertyMappingInterface
    {
        /** @var PropertyMappingInterface|\PHPUnit_Framework_MockObject_MockObject $mapping */
        $mapping = $this
            ->getMockBuilder(PropertyMappingInterface::class)
            ->setMethods(['getName', 'getConstraints'])
            ->getMockForAbstractClass()
        ;

        $mapping->expects(self::any())->method('getName')->willReturn($name);
        $mapping->expects(self::any())->method('getConstraints')->willReturn($constraints);

        return $mapping;
    }

    /**
     * @return ConstraintInterface
     */
    private function getConstraint(): ConstraintInterface
    {
        /** @var ConstraintInterface|\PHPUnit_Framework_MockObject_MockObject $constraint */
        $constraint = $this
            ->getMockBuilder(ConstraintInterface::class)
            ->setMethods(['validate'])
            ->getMockForAbstractClass()
        ;

        $constraint->expects(self::any())->method('validate')->willReturn([]);

        return $constraint;
    }
}
