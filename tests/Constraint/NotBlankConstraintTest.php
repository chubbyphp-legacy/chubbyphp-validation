<?php

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Error\Error;

/**
 * @covers \Chubbyphp\Validation\Constraint\NotBlankConstraint
 */
class NotBlankConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', null));
    }

    public function testWithNotBlank()
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', 'notblank'));
    }

    public function testWithBlankString()
    {
        $constraint = new NotBlankConstraint();

        $error = new Error('notblank', 'constraint.notblank.blank');

        self::assertEquals([$error], $constraint->validate('notblank', ''));
    }

    public function testWithBlankArray()
    {
        $constraint = new NotBlankConstraint();

        $error = new Error('notblank', 'constraint.notblank.blank');

        self::assertEquals([$error], $constraint->validate('notblank', []));
    }
}
