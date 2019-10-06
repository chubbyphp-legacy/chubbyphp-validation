<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NotBlankConstraint
 *
 * @internal
 */
final class NotBlankConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', null, $this->getContext()));
    }

    public function testWithNotBlankString(): void
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', 'value', $this->getContext()));
    }

    public function testWithNotBlankArray(): void
    {
        $constraint = new NotBlankConstraint();

        self::assertEquals([], $constraint->validate('notblank', ['value'], $this->getContext()));
    }

    public function testWithNotBlankStdClass(): void
    {
        $constraint = new NotBlankConstraint();

        $object = new \stdClass();
        $object->key = 'value';

        self::assertEquals([], $constraint->validate('notblank', $object, $this->getContext()));
    }

    public function testWithBlankString(): void
    {
        $constraint = new NotBlankConstraint();

        $error = new Error('notblank', 'constraint.notblank.blank');

        self::assertEquals([$error], $constraint->validate('notblank', '', $this->getContext()));
    }

    public function testWithBlankArray(): void
    {
        $constraint = new NotBlankConstraint();

        $error = new Error('notblank', 'constraint.notblank.blank');

        self::assertEquals([$error], $constraint->validate('notblank', [], $this->getContext()));
    }

    public function testWithBlankStdClass(): void
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
        /* @var ValidatorContextInterface|MockObject $context */
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
