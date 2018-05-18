<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMapping;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationPropertyMapping
 */
class ValidationPropertyMappingTest extends TestCase
{
    use MockForInterfaceTrait;

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
        /** @var ConstraintInterface|MockObject $constraint */
        $constraint = $this->getMockForInterface(ConstraintInterface::class);

        return $constraint;
    }

    /**
     * @return AccessorInterface
     */
    private function getAccessor(): AccessorInterface
    {
        /** @var AccessorInterface|MockObject $accessor */
        $accessor = $this->getMockForInterface(AccessorInterface::class);

        return $accessor;
    }
}
