<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\AllConstraint;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\AllConstraint
 *
 * @internal
 */
final class AllConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new AllConstraint();

        self::assertEquals([], $constraint->validate('traversable', null, $this->getContext()));
    }

    public function testWithInvalidValue(): void
    {
        $constraint = new AllConstraint();

        self::assertEquals([
            new Error('traversable[_all]', 'constraint.all.invalidtype', ['type' => 'string']),
        ], $constraint->validate('traversable', 'string', $this->getContext()));
    }

    public function testWithoutConstraint(): void
    {
        $constraint = new AllConstraint([]);

        self::assertEquals([], $constraint->validate('traversable', ['string'], $this->getContext(), $this->getValidator()));
    }

    public function testWithConstraint(): void
    {
        $constraint = new AllConstraint([$this->getConstraint()]);

        self::assertEquals([], $constraint->validate('traversable', ['string'], $this->getContext(), $this->getValidator()));
    }

    public function testWithConstraintAndError(): void
    {
        $constraint = new AllConstraint([$this->getConstraint(true)]);

        self::assertEquals([
            new Error('traversable[0]', 'key', ['value' => 'string']),
        ], $constraint->validate('traversable', ['string'], $this->getContext(), $this->getValidator()));
    }

    /**
     * @return ValidatorInterface
     */
    private function getValidator(): ValidatorInterface
    {
        /* @var ValidatorInterface|MockObject $validator */
        return $this->getMockByCalls(ValidatorInterface::class);
    }

    /**
     * @param bool $error
     *
     * @return ConstraintInterface
     */
    private function getConstraint(bool $error = false): ConstraintInterface
    {
        /** @var ConstraintInterface|MockObject $constraint */
        $constraint = $this
            ->getMockBuilder(ConstraintInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass()
        ;

        $constraint->expects(self::any())->method('validate')->willReturnCallback(
            function (
            string $path,
            $value,
            ValidatorContextInterface $context,
            ValidatorInterface $validator = null
        ) use ($error) {
                if (!$error) {
                    return [];
                }

                return [new Error($path, 'key', ['value' => $value])];
            }
        );

        return $constraint;
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
