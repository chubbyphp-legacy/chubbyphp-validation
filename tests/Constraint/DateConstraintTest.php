<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\DateConstraint;
use Chubbyphp\Validation\Error\Error;

/**
 * @covers \Chubbyphp\Validation\Constraint\DateConstraint
 */
final class DateConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new DateConstraint();

        self::assertEquals([], $constraint->validate('date', null));
    }

    public function testWithDateTime()
    {
        $constraint = new DateConstraint();

        self::assertEquals([], $constraint->validate('date', new \DateTime()));
    }

    public function testInvalidType()
    {
        $constraint = new DateConstraint();

        $error = new Error('date', 'constraint.date.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('date', []));
    }

    public function testWithDateString()
    {
        $constraint = new DateConstraint();

        self::assertEquals([], $constraint->validate('date', '2017-01-01'));
    }

    public function testWithInvalidDateString()
    {
        $constraint = new DateConstraint();

        $error = new Error('date', 'constraint.date.invalidvalue', ['input' => '2017-13-01']);

        self::assertEquals([$error], $constraint->validate('date', '2017-13-01'));
    }
}
