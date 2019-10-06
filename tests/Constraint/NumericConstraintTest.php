<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\NumericConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NumericConstraint
 *
 * @internal
 */
final class NumericConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', null, $this->getContext()));
    }

    public function testWithBlankValue(): void
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', '', $this->getContext()));
    }

    public function testWithInteger(): void
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', 1, $this->getContext()));
    }

    public function testWithFloat(): void
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', 1.1, $this->getContext()));
    }

    public function testWithString(): void
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', '1.1', $this->getContext()));
    }

    public function testWithInvalidType(): void
    {
        $constraint = new NumericConstraint();

        $error = new Error('numeric', 'constraint.numeric.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('numeric', [], $this->getContext()));
    }

    public function testWithoutNumericString(): void
    {
        $constraint = new NumericConstraint();

        $error = new Error('numeric', 'constraint.numeric.notnumeric', ['value' => 'test']);

        self::assertEquals([$error], $constraint->validate('numeric', 'test', $this->getContext()));
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /* @var ValidatorContextInterface|MockObject $context */
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
