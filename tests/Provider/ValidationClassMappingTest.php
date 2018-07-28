<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMapping;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationClassMapping
 */
class ValidationClassMappingTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetConstraints()
    {
        $constraint = $this->getConstraint();

        $classMapping = new ValidationClassMapping([$constraint], ['group1']);

        self::assertSame([$constraint], $classMapping->getConstraints());
    }

    public function testGetGroups()
    {
        $constraint = $this->getConstraint();

        $classMapping = new ValidationClassMapping([$constraint], ['group1']);

        self::assertSame(['group1'], $classMapping->getGroups());
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
