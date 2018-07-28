<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validator\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder
 */
class ValidationClassMappingBuilderTest extends TestCase
{
    use MockByCallsTrait;

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
        /** @var ConstraintInterface|MockObject $constraint */
        $constraint = $this->getMockByCalls(ConstraintInterface::class);

        return $constraint;
    }
}
