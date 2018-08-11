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
 */
final class BlankConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue()
    {
        $constraint = new BlankConstraint();

        self::assertEquals([], $constraint->validate('blank', null, $this->getContext()));
    }

    public function testWithBlankString()
    {
        $constraint = new BlankConstraint();

        self::assertEquals([], $constraint->validate('blank', '', $this->getContext()));
    }

    public function testWithBlankArray()
    {
        $constraint = new BlankConstraint();

        self::assertEquals([], $constraint->validate('blank', [], $this->getContext()));
    }

    public function testWithBlankStdClass()
    {
        $constraint = new BlankConstraint();

        self::assertEquals([], $constraint->validate('blank', new \stdClass(), $this->getContext()));
    }

    public function testWithNotBlankString()
    {
        $constraint = new BlankConstraint();

        $error = new Error('blank', 'constraint.blank.notblank');

        self::assertEquals([$error], $constraint->validate('blank', 'value', $this->getContext()));
    }

    public function testWithNotBlankArray()
    {
        $constraint = new BlankConstraint();

        $error = new Error('blank', 'constraint.blank.notblank');

        self::assertEquals([$error], $constraint->validate('blank', ['value'], $this->getContext()));
    }

    public function testWithNotBlankStdClass()
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
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        return $context;
    }
}
