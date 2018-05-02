<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NotBlankConstraint
 */
final class NotBlankConstraintTest extends TestCase
{
    public function testWithNullValue()
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', null, $this->getContext()));
    }

    public function testWithNotBlankString()
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', 'value', $this->getContext()));
    }

    public function testWithNotBlankArray()
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', ['value'], $this->getContext()));
    }

    public function testWithNotBlankStdClass()
    {
        $constraint = new NotBlankConstraint();

        $object = new \stdClass();
        $object->key = 'value';

        self::assertEquals([], $constraint->validate('notblank', $object, $this->getContext()));
    }

    public function testWithBlankString()
    {
        $constraint = new NotBlankConstraint();

        $error = new Error('notblank', 'constraint.notblank.blank');

        self::assertEquals([$error], $constraint->validate('notblank', '', $this->getContext()));
    }

    public function testWithBlankArray()
    {
        $constraint = new NotBlankConstraint();

        $error = new Error('notblank', 'constraint.notblank.blank');

        self::assertEquals([$error], $constraint->validate('notblank', [], $this->getContext()));
    }

    public function testWithBlankStdClass()
    {
        $constraint = new NotBlankConstraint();

        $error = new Error('notblank', 'constraint.notblank.blank');

        self::assertEquals([$error], $constraint->validate('notblank', new \stdClass(), $this->getContext()));
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
