<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\NullConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NullConstraint
 */
final class NullConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue()
    {
        $constraint = new NullConstraint();

        self::assertEquals([], $constraint->validate('null', null, $this->getContext()));
    }

    public function testWithBool()
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', false, $this->getContext()));
    }

    public function testWithInteger()
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', 5, $this->getContext()));
    }

    public function testWithFloat()
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', 5.5, $this->getContext()));
    }

    public function testWithString()
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', '', $this->getContext()));
    }

    public function testWithArray()
    {
        $constraint = new NullConstraint();

        $error = new Error('null', 'constraint.null.notnull');

        self::assertEquals([$error], $constraint->validate('null', [], $this->getContext()));
    }

    public function testWithStdClass()
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
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        return $context;
    }
}
