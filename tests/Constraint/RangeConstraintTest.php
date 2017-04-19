<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\RangeConstraint;
use Chubbyphp\Validation\Error\Error;

/**
 * @covers \Chubbyphp\Validation\Constraint\RangeConstraint
 */
final class RangeConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new RangeConstraint();

        self::assertEquals([], $constraint->validate('range', null));
    }

    public function testWithoutNumeric()
    {
        $constraint = new RangeConstraint();

        $error = new Error('range', 'constraint.range.notnumeric', ['input' => 'value']);

        self::assertEquals([$error], $constraint->validate('range', 'value'));
    }

    public function testWithoutMinAndMax()
    {
        $constraint = new RangeConstraint();

        self::assertEquals([], $constraint->validate('range', 1));
    }

    public function testWithMin()
    {
        $constraint = new RangeConstraint(1);

        self::assertEquals([], $constraint->validate('range', 1));
    }

    public function testWithMinButToLessValues()
    {
        $constraint = new RangeConstraint(2);

        $error = new Error('range', 'constraint.range.outofrange', ['input' => 1, 'min' => 2, 'max' => null]);

        self::assertEquals([$error], $constraint->validate('range', 1));
    }

    public function testWithMax()
    {
        $constraint = new RangeConstraint(null, 1);

        self::assertEquals([], $constraint->validate('range', 1));
    }

    public function testWithMaxButToManyValues()
    {
        $constraint = new RangeConstraint(null, 0);

        $error = new Error('range', 'constraint.range.outofrange', ['input' => 1, 'min' => null, 'max' => 0]);

        self::assertEquals([$error], $constraint->validate('range', 1));
    }

    public function testWithMinAndMax()
    {
        $constraint = new RangeConstraint(1, 2);

        self::assertEquals([], $constraint->validate('range', 1));
    }

    public function testWithMinAndMaxToLessValues()
    {
        $constraint = new RangeConstraint(1, 2);

        $error = new Error('range', 'constraint.range.outofrange', ['input' => 0, 'min' => 1, 'max' => 2]);

        self::assertEquals([$error], $constraint->validate('range', 0));
    }

    public function testWithMinAndMaxToManyValues()
    {
        $constraint = new RangeConstraint(1, 2);

        $error = new Error('range', 'constraint.range.outofrange', ['input' => 3, 'min' => 1, 'max' => 2]);

        self::assertEquals([$error], $constraint->validate('range', 3));
    }
}
