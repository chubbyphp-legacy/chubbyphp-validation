<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validator\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder
 */
class ValidationClassMappingBuilderTest extends TestCase
{
    public function testGetDefaultMapping()
    {
        $propertyMapping = ValidationClassMappingBuilder::create([])->getMapping();

        self::assertSame([], $propertyMapping->getConstraints());
        self::assertSame([], $propertyMapping->getGroups());
    }

    public function testGetMapping()
    {
        $constraint = $this->getConstraint();

        $propertyMapping = ValidationClassMappingBuilder::create([$constraint])
            ->setGroups(['group1'])
            ->getMapping();

        self::assertSame([$constraint], $propertyMapping->getConstraints());
        self::assertSame(['group1'], $propertyMapping->getGroups());
    }

    /**
     * @return ConstraintInterface
     */
    private function getConstraint(): ConstraintInterface
    {
        /** @var ConstraintInterface|\PHPUnit_Framework_MockObject_MockObject $constraint */
        $constraint = $this->getMockBuilder(ConstraintInterface::class)->getMockForAbstractClass();

        return $constraint;
    }
}
