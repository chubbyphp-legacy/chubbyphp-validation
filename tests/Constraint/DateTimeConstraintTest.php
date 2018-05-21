<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Constraint\DateTimeConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\DateTimeConstraint
 */
final class DateTimeConstraintTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testWithNullValue()
    {
        $constraint = new DateTimeConstraint();

        self::assertEquals([], $constraint->validate('date', null, $this->getContext()));
    }

    public function testWithBlankValue()
    {
        $constraint = new DateTimeConstraint();

        self::assertEquals([], $constraint->validate('date', '', $this->getContext()));
    }

    public function testWithDate()
    {
        $constraint = new DateTimeConstraint('Y-m-d');

        $date = new \DateTime('2018-05-21');
        $date->setTime(0, 0, 0);

        self::assertEquals([], $constraint->validate('date', $date, $this->getContext()));
    }

    public function testWithDateTimeExpectedDate()
    {
        $constraint = new DateTimeConstraint('Y-m-d');

        $date = new \DateTime('2018-05-21');
        $date->setTime(2, 0, 0);

        $error = new Error(
            'date',
            'constraint.date.format',
            [
                'format' => 'Y-m-d',
                'value' => '2018-05-21T02:00:00+02:00',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', $date, $this->getContext()));
    }

    public function testWithDateTime()
    {
        $constraint = new DateTimeConstraint();

        $date = new \DateTime('2018-05-21');
        $date->setTime(2, 0, 0);

        self::assertEquals([], $constraint->validate('date', $date, $this->getContext()));
    }

    public function testInvalidType()
    {
        $constraint = new DateTimeConstraint();

        $error = new Error('date', 'constraint.date.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('date', [], $this->getContext()));
    }

    public function testWithDateString()
    {
        $constraint = new DateTimeConstraint('Y-m-d');

        self::assertEquals([], $constraint->validate('date', '2017-01-01', $this->getContext()));
    }

    public function testWithInvalidDateString()
    {
        $constraint = new DateTimeConstraint('Y-m-d');

        $error = new Error(
            'date',
            'constraint.date.warning',
            [
                'message' => 'The parsed date was invalid',
                'format' => 'Y-m-d',
                'value' => '2017-13-01',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', '2017-13-01', $this->getContext()));
    }

    public function testWithDateTimeString()
    {
        $constraint = new DateTimeConstraint();

        self::assertEquals([], $constraint->validate('date', '2017-01-01 07:00:00', $this->getContext()));
    }

    public function testWithInvalidDateTimeString()
    {
        $constraint = new DateTimeConstraint();

        $error = new Error(
            'date',
            'constraint.date.warning',
            [
                'message' => 'The parsed date was invalid',
                'format' => 'Y-m-d H:i:s',
                'value' => '2017-13-01 07:00:00',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', '2017-13-01 07:00:00', $this->getContext()));
    }

    public function testWithInvalidDateTimeFormat()
    {
        $constraint = new DateTimeConstraint();

        $error = new Error(
            'date',
            'constraint.date.error',
            [
                'message' => 'Trailing data',
                'format' => 'Y-m-d H:i:s',
                'value' => '2017-12-01 07:00:00:00',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', '2017-12-01 07:00:00:00', $this->getContext()));
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockForInterface(ValidatorContextInterface::class);

        return $context;
    }
}
