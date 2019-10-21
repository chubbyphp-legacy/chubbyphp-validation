<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\TrueConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\TrueConstraint
 *
 * @internal
 */
final class TrueConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new TrueConstraint();

        self::assertEquals([], $constraint->validate('true', null, $this->getContext()));
    }

    public function testWithTrue(): void
    {
        $constraint = new TrueConstraint();

        self::assertEquals([], $constraint->validate('true', true, $this->getContext()));
    }

    public function testWithFalse(): void
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', false, $this->getContext()));
    }

    public function testWithInteger(): void
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', 5, $this->getContext()));
    }

    public function testWithFloat(): void
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', 5.5, $this->getContext()));
    }

    public function testWithString(): void
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', '', $this->getContext()));
    }

    public function testWithArray(): void
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', [], $this->getContext()));
    }

    public function testWithStdClass(): void
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', new \stdClass(), $this->getContext()));
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /* @var ValidatorContextInterface|MockObject $context */
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
