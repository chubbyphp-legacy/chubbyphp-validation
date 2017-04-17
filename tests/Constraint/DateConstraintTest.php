<?php

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\DateConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorInterface;

/**
 * @covers \Chubbyphp\Validation\Constraint\DateConstraint
 */
class DateConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new DateConstraint();

        self::assertEquals([], $constraint->validate($this->getValidator(), 'date', null));
    }

    public function testWithDateTime()
    {
        $constraint = new DateConstraint();

        self::assertEquals([], $constraint->validate($this->getValidator(), 'date', new \DateTime()));
    }

    public function testInvalidType()
    {
        $constraint = new DateConstraint();

        $error = new Error('date', 'constraint.date.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate($this->getValidator(), 'date', []));
    }

    public function testWithDateString()
    {
        $constraint = new DateConstraint();

        self::assertEquals([], $constraint->validate($this->getValidator(), 'date', '2017-01-01'));
    }

    public function testWithInvalidDateString()
    {
        $constraint = new DateConstraint();

        $error = new Error('date', 'constraint.date.notparseable', ['date' => '2017-13-01']);

        self::assertEquals([$error], $constraint->validate($this->getValidator(), 'date', '2017-13-01'));
    }

    /**
     * @return ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getValidator(): ValidatorInterface
    {
        return $this
            ->getMockBuilder(ValidatorInterface::class)
            ->getMockForAbstractClass();
    }
}
