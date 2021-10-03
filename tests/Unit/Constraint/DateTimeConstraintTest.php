<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\DateTimeConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\DateTimeConstraint
 *
 * @internal
 */
final class DateTimeConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new DateTimeConstraint();

        self::assertEquals([], $constraint->validate('date', null, $this->getContext()));
    }

    public function testWithBlankValue(): void
    {
        $constraint = new DateTimeConstraint();

        self::assertEquals([], $constraint->validate('date', '', $this->getContext()));
    }

    public function testWithDate(): void
    {
        $constraint = new DateTimeConstraint('Y-m-d');

        $date = new \DateTimeImmutable('2018-05-21');
        $date->setTime(0, 0, 0);

        self::assertEquals([], $constraint->validate('date', $date, $this->getContext()));
    }

    public function testWithDateTimeExpectedDate(): void
    {
        $constraint = new DateTimeConstraint('Y-m-d');

        $date = new \DateTimeImmutable('2018-05-21T00:00:00+02:00');
        $date = $date->setTime(2, 0, 0);

        $error = new Error(
            'date',
            'constraint.datetime.format',
            [
                'format' => 'Y-m-d',
                'value' => '2018-05-21T02:00:00+02:00',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', $date, $this->getContext()));
    }

    public function testWithDateTime(): void
    {
        $constraint = new DateTimeConstraint();

        $date = new \DateTimeImmutable('2018-05-21');
        $date->setTime(2, 0, 0);

        self::assertEquals([], $constraint->validate('date', $date, $this->getContext()));
    }

    public function testInvalidType(): void
    {
        $constraint = new DateTimeConstraint();

        $error = new Error('date', 'constraint.datetime.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('date', [], $this->getContext()));
    }

    public function testWithDateString(): void
    {
        $constraint = new DateTimeConstraint('Y-m-d');

        self::assertEquals([], $constraint->validate('date', '2017-01-01', $this->getContext()));
    }

    public function testWithInvalidDateString(): void
    {
        $constraint = new DateTimeConstraint('Y-m-d');

        $error = new Error(
            'date',
            'constraint.datetime.warning',
            [
                'message' => 'The parsed date was invalid',
                'format' => 'Y-m-d',
                'value' => '2017-13-01',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', '2017-13-01', $this->getContext()));
    }

    public function testWithDateTimeString(): void
    {
        $constraint = new DateTimeConstraint();

        self::assertEquals([], $constraint->validate('date', '2017-01-01 07:00:00', $this->getContext()));
    }

    public function testWithInvalidDateTimeString(): void
    {
        $constraint = new DateTimeConstraint();

        $error = new Error(
            'date',
            'constraint.datetime.warning',
            [
                'message' => 'The parsed date was invalid',
                'format' => 'Y-m-d H:i:s',
                'value' => '2017-13-01 07:00:00',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', '2017-13-01 07:00:00', $this->getContext()));
    }

    public function testWithInvalidDateTimeFormat(): void
    {
        $constraint = new DateTimeConstraint();

        $error = new Error(
            'date',
            'constraint.datetime.error',
            [
                'message' => 'Trailing data',
                'format' => 'Y-m-d H:i:s',
                'value' => '2017-12-01 07:00:00:00',
            ]
        );

        self::assertEquals([$error], $constraint->validate('date', '2017-12-01 07:00:00:00', $this->getContext()));
    }

    private function getContext(): ValidatorContextInterface
    {
        // @var ValidatorContextInterface|MockObject $context
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
