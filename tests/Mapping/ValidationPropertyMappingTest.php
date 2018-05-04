<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMapping;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationPropertyMapping
 */
class ValidationPropertyMappingTest extends TestCase
{
    public function testGetName()
    {
        $constraint = $this->getConstraint();
        $accessor = $this->getAccessor();

        $propertyMapping = new ValidationPropertyMapping('name', [$constraint], ['group1'], $accessor);

        self::assertSame('name', $propertyMapping->getName());
    }

    public function testGetConstraints()
    {
        $constraint = $this->getConstraint();
        $accessor = $this->getAccessor();

        $propertyMapping = new ValidationPropertyMapping('name', [$constraint], ['group1'], $accessor);

        self::assertSame([$constraint], $propertyMapping->getConstraints());
    }

    public function testGetGroups()
    {
        $constraint = $this->getConstraint();
        $accessor = $this->getAccessor();

        $propertyMapping = new ValidationPropertyMapping('name', [$constraint], ['group1'], $accessor);

        self::assertSame(['group1'], $propertyMapping->getGroups());
    }

    public function testGetAccessor()
    {
        $constraint = $this->getConstraint();
        $accessor = $this->getAccessor();

        $propertyMapping = new ValidationPropertyMapping('name', [$constraint], ['group1'], $accessor);

        self::assertSame($accessor, $propertyMapping->getAccessor());
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

    /**
     * @return AccessorInterface
     */
    private function getAccessor(): AccessorInterface
    {
        /** @var AccessorInterface|\PHPUnit_Framework_MockObject_MockObject $accessor */
        $accessor = $this->getMockBuilder(AccessorInterface::class)->getMockForAbstractClass();

        return $accessor;
    }
}
