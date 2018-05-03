<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

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
    public function testWithNullValue()
    {
        $constraint = new DateTimeConstraint();

        self::assertEquals([], $constraint->validate('date', null, $this->getContext()));
    }

    public function testWithDateTime()
    {
        $constraint = new DateTimeConstraint();

        self::assertEquals([], $constraint->validate('date', new \DateTime(), $this->getContext()));
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
                'code' => 10,
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
                'code' => 19,
                'message' => 'The parsed date was invalid',
                'format' => 'Y-m-d H:i:s',
                'value' => '2017-13-01 07:00:00',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', '2017-13-01 07:00:00', $this->getContext()));
    }

    public function testWithInvalidFormat()
    {
        $constraint = new DateTimeConstraint();

        $errors = [
            new Error(
                'date',
                'constraint.date.warning',
                [
                    'code' => 10,
                    'message' => 'The parsed date was invalid',
                    'format' => 'Y-m-d H:i:s',
                    'value' => '2017-13-01',
                ]
            ),
            new Error(
                'date',
                'constraint.date.error',
                [
                    'code' => 10,
                    'message' => 'Data missing',
                    'format' => 'Y-m-d H:i:s',
                    'value' => '2017-13-01',
                ]
            ),
        ];

        self::assertEquals($errors, $constraint->validate('date', '2017-13-01', $this->getContext()));
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
