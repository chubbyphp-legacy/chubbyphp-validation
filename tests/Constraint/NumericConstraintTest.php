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
 */
final class NumericConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', null, $this->getContext()));
    }

    public function testWithBlankValue()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', '', $this->getContext()));
    }

    public function testWithInteger()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', 1, $this->getContext()));
    }

    public function testWithFloat()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', 1.1, $this->getContext()));
    }

    public function testWithString()
    {
        $constraint = new NumericConstraint();

        self::assertEquals([], $constraint->validate('numeric', '1.1', $this->getContext()));
    }

    public function testWithInvalidType()
    {
        $constraint = new NumericConstraint();

        $error = new Error('numeric', 'constraint.numeric.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('numeric', [], $this->getContext()));
    }

    public function testWithoutNumericString()
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
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        return $context;
    }
}
