<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\TrueConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\TrueConstraint
 */
final class TrueConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue()
    {
        $constraint = new TrueConstraint();

        self::assertEquals([], $constraint->validate('true', null, $this->getContext()));
    }

    public function testWithTrue()
    {
        $constraint = new TrueConstraint();

        self::assertEquals([], $constraint->validate('true', true, $this->getContext()));
    }

    public function testWithFalse()
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', false, $this->getContext()));
    }

    public function testWithInteger()
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', 5, $this->getContext()));
    }

    public function testWithFloat()
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', 5.5, $this->getContext()));
    }

    public function testWithString()
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', '', $this->getContext()));
    }

    public function testWithArray()
    {
        $constraint = new TrueConstraint();

        $error = new Error('true', 'constraint.true.nottrue');

        self::assertEquals([$error], $constraint->validate('true', [], $this->getContext()));
    }

    public function testWithStdClass()
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
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        return $context;
    }
}
