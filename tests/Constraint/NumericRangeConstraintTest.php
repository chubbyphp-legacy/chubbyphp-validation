<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\NumericRangeConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NumericRangeConstraint
 */
final class NumericRangeConstraintTest extends TestCase
{
    public function testWithoutNumeric()
    {
        $constraint = new NumericRangeConstraint();

        self::assertEquals([], $constraint->validate('range', 'value', $this->getContext()));
    }

    public function testWithoutMinAndMax()
    {
        $constraint = new NumericRangeConstraint();

        self::assertEquals([], $constraint->validate('range', 1, $this->getContext()));
    }

    public function testWithMin()
    {
        $constraint = new NumericRangeConstraint(1);

        self::assertEquals([], $constraint->validate('range', 1, $this->getContext()));
    }

    public function testWithMinButToLessValues()
    {
        $constraint = new NumericRangeConstraint(2);

        $error = new Error('range', 'constraint.numericrange.outofrange', ['value' => 1, 'min' => 2, 'max' => null]);

        self::assertEquals([$error], $constraint->validate('range', 1, $this->getContext()));
    }

    public function testWithMax()
    {
        $constraint = new NumericRangeConstraint(null, 1);

        self::assertEquals([], $constraint->validate('range', 1, $this->getContext()));
    }

    public function testWithMaxButToManyValues()
    {
        $constraint = new NumericRangeConstraint(null, 0);

        $error = new Error('range', 'constraint.numericrange.outofrange', ['value' => 1, 'min' => null, 'max' => 0]);

        self::assertEquals([$error], $constraint->validate('range', 1, $this->getContext()));
    }

    public function testWithMinAndMax()
    {
        $constraint = new NumericRangeConstraint(1, 2);

        self::assertEquals([], $constraint->validate('range', 1, $this->getContext()));
    }

    public function testWithMinAndMaxToLessValues()
    {
        $constraint = new NumericRangeConstraint(1, 2);

        $error = new Error('range', 'constraint.numericrange.outofrange', ['value' => 0, 'min' => 1, 'max' => 2]);

        self::assertEquals([$error], $constraint->validate('range', 0, $this->getContext()));
    }

    public function testWithMinAndMaxToManyValues()
    {
        $constraint = new NumericRangeConstraint(1, 2);

        $error = new Error('range', 'constraint.numericrange.outofrange', ['value' => 3, 'min' => 1, 'max' => 2]);

        self::assertEquals([$error], $constraint->validate('range', 3, $this->getContext()));
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockBuilder(ValidatorContextInterface::class)->getMockForAbstractClass();

        return $context;
    }
}
