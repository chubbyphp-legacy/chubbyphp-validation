<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NotNullConstraint
 */
final class NotNullConstraintTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testWithNullValue()
    {
        $constraint = new NotNullConstraint();

        $error = new Error('notnull', 'constraint.notnull.null');

        self::assertEquals([$error], $constraint->validate('notnull', null, $this->getContext()));
    }

    public function testWithBool()
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', false, $this->getContext()));
    }

    public function testWithInteger()
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', 5, $this->getContext()));
    }

    public function testWithFloat()
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', 5.5, $this->getContext()));
    }

    public function testWithString()
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', '', $this->getContext()));
    }

    public function testWithArray()
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', [], $this->getContext()));
    }

    public function testWithStdClass()
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', new \stdClass(), $this->getContext()));
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
