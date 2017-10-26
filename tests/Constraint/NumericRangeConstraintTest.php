<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\NumericRangeConstraint;
use Chubbyphp\Validation\Error\Error;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NumericRangeConstraint
 */
final class NumericRangeConstraintTest extends TestCase
{
    public function testWithoutNumeric()
    {
        $constraint = new NumericRangeConstraint();

        self::assertEquals([], $constraint->validate('range', 'value'));
    }

    public function testWithoutMinAndMax()
    {
        $constraint = new NumericRangeConstraint();

        self::assertEquals([], $constraint->validate('range', 1));
    }

    public function testWithMin()
    {
        $constraint = new NumericRangeConstraint(1);

        self::assertEquals([], $constraint->validate('range', 1));
    }

    public function testWithMinButToLessValues()
    {
        $constraint = new NumericRangeConstraint(2);

        $error = new Error('range', 'constraint.numericrange.outofrange', ['input' => 1, 'min' => 2, 'max' => null]);

        self::assertEquals([$error], $constraint->validate('range', 1));
    }

    public function testWithMax()
    {
        $constraint = new NumericRangeConstraint(null, 1);

        self::assertEquals([], $constraint->validate('range', 1));
    }

    public function testWithMaxButToManyValues()
    {
        $constraint = new NumericRangeConstraint(null, 0);

        $error = new Error('range', 'constraint.numericrange.outofrange', ['input' => 1, 'min' => null, 'max' => 0]);

        self::assertEquals([$error], $constraint->validate('range', 1));
    }

    public function testWithMinAndMax()
    {
        $constraint = new NumericRangeConstraint(1, 2);

        self::assertEquals([], $constraint->validate('range', 1));
    }

    public function testWithMinAndMaxToLessValues()
    {
        $constraint = new NumericRangeConstraint(1, 2);

        $error = new Error('range', 'constraint.numericrange.outofrange', ['input' => 0, 'min' => 1, 'max' => 2]);

        self::assertEquals([$error], $constraint->validate('range', 0));
    }

    public function testWithMinAndMaxToManyValues()
    {
        $constraint = new NumericRangeConstraint(1, 2);

        $error = new Error('range', 'constraint.numericrange.outofrange', ['input' => 3, 'min' => 1, 'max' => 2]);

        self::assertEquals([$error], $constraint->validate('range', 3));
    }
}
