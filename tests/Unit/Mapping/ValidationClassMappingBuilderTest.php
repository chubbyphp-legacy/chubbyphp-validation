<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder
 *
 * @internal
 */
final class ValidationClassMappingBuilderTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetDefaultMapping(): void
    {
        $propertyMapping = ValidationClassMappingBuilder::create([])->getMapping();

        self::assertSame([], $propertyMapping->getConstraints());
        self::assertSame([], $propertyMapping->getGroups());
    }

    public function testGetMapping(): void
    {
        $constraint = $this->getConstraint();

        $propertyMapping = ValidationClassMappingBuilder::create([$constraint])
            ->setGroups(['group1'])
            ->getMapping()
        ;

        self::assertSame([$constraint], $propertyMapping->getConstraints());
        self::assertSame(['group1'], $propertyMapping->getGroups());
    }

    private function getConstraint(): ConstraintInterface
    {
        // @var ConstraintInterface|MockObject $constraint
        return $this->getMockByCalls(ConstraintInterface::class);
    }
}
