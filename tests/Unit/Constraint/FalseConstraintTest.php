<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\FalseConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\FalseConstraint
 *
 * @internal
 */
final class FalseConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new FalseConstraint();

        self::assertEquals([], $constraint->validate('false', null, $this->getContext()));
    }

    public function testWithFalse(): void
    {
        $constraint = new FalseConstraint();

        self::assertEquals([], $constraint->validate('false', false, $this->getContext()));
    }

    public function testWithTrue(): void
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', true, $this->getContext()));
    }

    public function testWithInteger(): void
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', 5, $this->getContext()));
    }

    public function testWithFloat(): void
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', 5.5, $this->getContext()));
    }

    public function testWithString(): void
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', '', $this->getContext()));
    }

    public function testWithArray(): void
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', [], $this->getContext()));
    }

    public function testWithStdClass(): void
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', new \stdClass(), $this->getContext()));
    }

    private function getContext(): ValidatorContextInterface
    {
        // @var ValidatorContextInterface|MockObject $context
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
