<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\CountConstraint;
use Chubbyphp\Validation\Error\Error;

/**
 * @covers \Chubbyphp\Validation\Constraint\CountConstraint
 */
final class CountConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new CountConstraint();

        self::assertEquals([], $constraint->validate('count', null));
    }

    public function testInvalidType()
    {
        $constraint = new CountConstraint();

        $error = new Error('count', 'constraint.count.invalidtype', ['type' => 'string']);

        self::assertEquals([$error], $constraint->validate('count', 'value'));
    }

    public function testWithoutMinAndMax()
    {
        $constraint = new CountConstraint();

        self::assertEquals([], $constraint->validate('count', ['value']));
    }

    public function testWithMin()
    {
        $constraint = new CountConstraint(1);

        self::assertEquals([], $constraint->validate('count', ['value']));
    }

    public function testWithMinButToLessValues()
    {
        $constraint = new CountConstraint(2);

        $error = new Error('count', 'constraint.count.outofrange', ['count' => 1, 'min' => 2, 'max' => null]);

        self::assertEquals([$error], $constraint->validate('count', ['value']));
    }

    public function testWithMax()
    {
        $constraint = new CountConstraint(null, 1);

        self::assertEquals([], $constraint->validate('count', ['value']));
    }

    public function testWithMaxButToManyValues()
    {
        $constraint = new CountConstraint(null, 0);

        $error = new Error('count', 'constraint.count.outofrange', ['count' => 1, 'min' => null, 'max' => 0]);

        self::assertEquals([$error], $constraint->validate('count', ['value']));
    }

    public function testWithMinAndMax()
    {
        $constraint = new CountConstraint(1, 2);

        self::assertEquals([], $constraint->validate('count', ['value']));
    }

    public function testWithMinAndMaxToLessValues()
    {
        $constraint = new CountConstraint(1, 2);

        $error = new Error('count', 'constraint.count.outofrange', ['count' => 0, 'min' => 1, 'max' => 2]);

        self::assertEquals([$error], $constraint->validate('count', []));
    }

    public function testWithMinAndMaxToManyValues()
    {
        $constraint = new CountConstraint(1, 2);

        $error = new Error('count', 'constraint.count.outofrange', ['count' => 3, 'min' => 1, 'max' => 2]);

        self::assertEquals([$error], $constraint->validate('count', ['value', 'value', 'value']));
    }
}
