<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\NullConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NullConstraint
 *
 * @internal
 */
final class NullConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new NullConstraint();

        self::assertEquals([], $constraint->validate('null', null, $this->getContext()));
    }

    public function testWithBool(): void
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', false, $this->getContext()));
    }

    public function testWithInteger(): void
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', 5, $this->getContext()));
    }

    public function testWithFloat(): void
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', 5.5, $this->getContext()));
    }

    public function testWithString(): void
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', '', $this->getContext()));
    }

    public function testWithArray(): void
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', [], $this->getContext()));
    }

    public function testWithStdClass(): void
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', new \stdClass(), $this->getContext()));
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
