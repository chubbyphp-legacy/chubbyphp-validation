<?php

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\EmailConstraint;
use Chubbyphp\Validation\Error\Error;

/**
 * @covers \Chubbyphp\Validation\Constraint\EmailConstraint
 */
class EmailConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new EmailConstraint();

        self::assertEquals([], $constraint->validate('email', null));
    }

    public function testInvalidType()
    {
        $constraint = new EmailConstraint();

        $error = new Error('email', 'constraint.email.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('email', []));
    }

    public function testWithEmail()
    {
        $constraint = new EmailConstraint();

        self::assertEquals([], $constraint->validate('email', 'name@domain.tld'));
    }

    public function testWithInvalidEmail()
    {
        $constraint = new EmailConstraint();

        $error = new Error('email', 'constraint.email.invalidformat', ['input' => 'name']);

        self::assertEquals([$error], $constraint->validate('email', 'name'));
    }
}
