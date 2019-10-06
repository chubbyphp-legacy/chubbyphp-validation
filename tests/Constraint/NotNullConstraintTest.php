<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\NotNullConstraint
 *
 * @internal
 */
final class NotNullConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new NotNullConstraint();

        $error = new Error('notnull', 'constraint.notnull.null');

        self::assertEquals([$error], $constraint->validate('notnull', null, $this->getContext()));
    }

    public function testWithBool(): void
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', false, $this->getContext()));
    }

    public function testWithInteger(): void
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', 5, $this->getContext()));
    }

    public function testWithFloat(): void
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', 5.5, $this->getContext()));
    }

    public function testWithString(): void
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', '', $this->getContext()));
    }

    public function testWithArray(): void
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', [], $this->getContext()));
    }

    public function testWithStdClass(): void
    {
        $constraint = new NotNullConstraint();

        self::assertEquals([], $constraint->validate('notnull', new \stdClass(), $this->getContext()));
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
