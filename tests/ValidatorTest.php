<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Tests\Validation\Resources\Model;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Mapping\PropertyMappingInterface;
use Chubbyphp\Validation\Registry\ObjectMappingRegistryInterface;
use Chubbyphp\Validation\Validator;
use Chubbyphp\Validation\ValidatorInterface;

/**
 * @covers \Chubbyphp\Validation\Validator
 */
final class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutErrors()
    {
        $objectMappingRegistry = $this->getObjectMappingRegistry([
            Model::class => $this->getObjectMapping(
                Model::class,
                [
                    $this->getConstraint([])
                ],
                [
                    $this->getPropertyMapping('name', [$this->getConstraint([])])
                ]
            )
        ]);

        $validator = new Validator($objectMappingRegistry);

        $model = new Model();
        $model->setName('name');

        $errors = $validator->validateObject($model);

        self::assertEquals([], $errors);
    }

    public function testWithErrors()
    {
        $objectMappingRegistry = $this->getObjectMappingRegistry([
            Model::class => $this->getObjectMapping(
                Model::class,
                [
                    $this->getConstraint([
                        new Error('name', 'constraint.unique.notunique',[])
                    ])
                ],
                [
                    $this->getPropertyMapping('name', [
                        $this->getConstraint([
                            new Error('name', 'constraint.notnull.null', [])
                        ]),
                        $this->getConstraint([
                            new Error('name', 'constraint.notblank.blank', [])
                        ])
                    ])
                ]
            )
        ]);

        $validator = new Validator($objectMappingRegistry);

        $model = new Model();

        $errors = $validator->validateObject($model);

        self::assertEquals([
            new Error('name', 'constraint.notnull.null', []),
            new Error('name', 'constraint.notblank.blank', []),
            new Error('name', 'constraint.unique.notunique', []),
        ], $errors);
    }

    /**
     * @param ObjectMappingInterface[] $mappings
     * @return ObjectMappingRegistryInterface
     */
    private function getObjectMappingRegistry(array $mappings): ObjectMappingRegistryInterface {
        /** @var ObjectMappingRegistryInterface|\PHPUnit_Framework_MockObject_MockObject $registry */
        $registry = $this
            ->getMockBuilder(ObjectMappingRegistryInterface::class)
            ->setMethods(['getObjectMappingForClass'])
            ->getMockForAbstractClass()
        ;

        $registry->expects(self::any())->method('getObjectMappingForClass')->willReturnCallback(
            function (string $class) use ($mappings) {
                if (isset($mappings[$class])) {
                    return $mappings[$class];
                }

                return null;
            }
        );

        return $registry;
    }

    /**
     * @param string $class
     * @param array $constraints
     * @param array $propertyMappings
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
     * @param array $constraints
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
     * @param ErrorInterface[] $errors
     * @return ConstraintInterface
     */
    private function getConstraint(array $errors): ConstraintInterface
    {
        /** @var ConstraintInterface|\PHPUnit_Framework_MockObject_MockObject $constraint */
        $constraint = $this
            ->getMockBuilder(ConstraintInterface::class)
            ->setMethods(['validate'])
            ->getMockForAbstractClass()
        ;

        $constraint->expects(self::any())->method('validate')->willReturnCallback(
            function(string $path, $input, ValidatorInterface $validator = null) use ($errors) {
                return $errors;
            }
        );

        return $constraint;
    }
}
