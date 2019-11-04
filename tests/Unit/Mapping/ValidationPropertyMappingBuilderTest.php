<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validator\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationPropertyMappingBuilder
 *
 * @internal
 */
final class ValidationPropertyMappingBuilderTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetDefaultMapping(): void
    {
        $propertyMapping = ValidationPropertyMappingBuilder::create('name', [])->getMapping();

        self::assertSame('name', $propertyMapping->getName());
        self::assertSame([], $propertyMapping->getConstraints());
        self::assertSame([], $propertyMapping->getGroups());
        self::assertInstanceOf(AccessorInterface::class, $propertyMapping->getAccessor());
    }

    public function testGetMapping(): void
    {
        $constraint = $this->getConstraint();

        $accessor = $this->getAccessor();

        $propertyMapping = ValidationPropertyMappingBuilder::create('name', [$constraint])
            ->setGroups(['group1'])
            ->setAccessor($accessor)
            ->getMapping()
        ;

        self::assertSame('name', $propertyMapping->getName());
        self::assertSame([$constraint], $propertyMapping->getConstraints());
        self::assertSame(['group1'], $propertyMapping->getGroups());
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
