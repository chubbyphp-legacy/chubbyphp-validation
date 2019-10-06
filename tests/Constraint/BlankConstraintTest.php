<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\BlankConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\BlankConstraint
 *
 * @internal
 */
final class BlankConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new BlankConstraint();

        self::assertEquals([], $constraint->validate('blank', null, $this->getContext()));
    }

    public function testWithBlankString(): void
    {
        $constraint = new BlankConstraint();

        self::assertEquals([], $constraint->validate('blank', '', $this->getContext()));
    }

    public function testWithBlankArray(): void
    {
        $constraint = new BlankConstraint();

        self::assertEquals([], $constraint->validate('blank', [], $this->getContext()));
    }

    public function testWithBlankStdClass(): void
    {
        $constraint = new BlankConstraint();

        self::assertEquals([], $constraint->validate('blank', new \stdClass(), $this->getContext()));
    }

    public function testWithNotBlankString(): void
    {
        $constraint = new BlankConstraint();

        $error = new Error('blank', 'constraint.blank.notblank');

        self::assertEquals([$error], $constraint->validate('blank', 'value', $this->getContext()));
    }

    public function testWithNotBlankArray(): void
    {
        $constraint = new BlankConstraint();

        $error = new Error('blank', 'constraint.blank.notblank');

        self::assertEquals([$error], $constraint->validate('blank', ['value'], $this->getContext()));
    }

    public function testWithNotBlankStdClass(): void
    {
        $constraint = new BlankConstraint();

        $object = new \stdClass();
        $object->key = 'value';

        $error = new Error('blank', 'constraint.blank.notblank');

        self::assertEquals([$error], $constraint->validate('blank', $object, $this->getContext()));
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
