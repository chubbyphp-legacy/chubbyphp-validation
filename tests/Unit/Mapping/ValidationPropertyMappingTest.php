<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMapping;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationPropertyMapping
 *
 * @internal
 */
final class ValidationPropertyMappingTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetName(): void
    {
        $constraint = $this->getConstraint();
        $accessor = $this->getAccessor();

        $propertyMapping = new ValidationPropertyMapping('name', [$constraint], ['group1'], $accessor);

        self::assertSame('name', $propertyMapping->getName());
    }

    public function testGetConstraints(): void
    {
        $constraint = $this->getConstraint();
        $accessor = $this->getAccessor();

        $propertyMapping = new ValidationPropertyMapping('name', [$constraint], ['group1'], $accessor);

        self::assertSame([$constraint], $propertyMapping->getConstraints());
    }

    public function testGetGroups(): void
    {
        $constraint = $this->getConstraint();
        $accessor = $this->getAccessor();

        $propertyMapping = new ValidationPropertyMapping('name', [$constraint], ['group1'], $accessor);

        self::assertSame(['group1'], $propertyMapping->getGroups());
    }

    public function testGetAccessor(): void
    {
        $constraint = $this->getConstraint();
        $accessor = $this->getAccessor();

        $propertyMapping = new ValidationPropertyMapping('name', [$constraint], ['group1'], $accessor);

        self::assertSame($accessor, $propertyMapping->getAccessor());
    }

    private function getConstraint(): ConstraintInterface
    {
        /* @var ConstraintInterface|MockObject $constraint */
        return $this->getMockByCalls(ConstraintInterface::class);
    }

    private function getAccessor(): AccessorInterface
    {
        /* @var AccessorInterface|MockObject $accessor */
        return $this->getMockByCalls(AccessorInterface::class);
    }
}
