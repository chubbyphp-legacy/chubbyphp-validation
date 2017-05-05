<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Error\Error;

/**
 * @covers \Chubbyphp\Validation\Constraint\NotBlankConstraint
 */
final class NotBlankConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', null));
    }

    public function testWithNotBlankString()
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', 'value'));
    }

    public function testWithNotBlankArray()
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', ['value']));
    }

    public function testWithNotBlankStdClass()
    {
        $constraint = new NotBlankConstraint();

        $object = new \stdClass();
        $object->key = 'value';

        self::assertEquals([], $constraint->validate('notblank', $object));
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

    public function testWithBlankStdClass()
    {
        $constraint = new NotBlankConstraint();

        $error = new Error('notblank', 'constraint.notblank.blank');

        self::assertEquals([$error], $constraint->validate('notblank', new \stdClass()));
    }
}
