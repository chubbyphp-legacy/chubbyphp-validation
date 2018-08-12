<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\FalseConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\FalseConstraint
 */
final class FalseConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue()
    {
        $constraint = new FalseConstraint();

        self::assertEquals([], $constraint->validate('false', null, $this->getContext()));
    }

    public function testWithFalse()
    {
        $constraint = new FalseConstraint();

        self::assertEquals([], $constraint->validate('false', false, $this->getContext()));
    }

    public function testWithTrue()
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', true, $this->getContext()));
    }

    public function testWithInteger()
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', 5, $this->getContext()));
    }

    public function testWithFloat()
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', 5.5, $this->getContext()));
    }

    public function testWithString()
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', '', $this->getContext()));
    }

    public function testWithArray()
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', [], $this->getContext()));
    }

    public function testWithStdClass()
    {
        $constraint = new FalseConstraint();

        $error = new Error('false', 'constraint.false.notfalse');

        self::assertEquals([$error], $constraint->validate('false', new \stdClass(), $this->getContext()));
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        return $context;
    }
}
