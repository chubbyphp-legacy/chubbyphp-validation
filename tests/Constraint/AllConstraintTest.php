<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Constraint\AllConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\AllConstraint
 */
final class AllConstraintTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testWithNullValue()
    {
        $constraint = new AllConstraint();

        self::assertEquals([], $constraint->validate('traversable', null, $this->getContext()));
    }

    public function testWithInvalidValue()
    {
        $constraint = new AllConstraint();

        self::assertEquals([
            new Error('traversable[_all]', 'constraint.all.invalidtype', ['type' => 'string']),
        ], $constraint->validate('traversable', 'string', $this->getContext()));
    }

    public function testWithoutConstraint()
    {
        $constraint = new AllConstraint([]);

        self::assertEquals([], $constraint->validate('traversable', ['string'], $this->getContext(), $this->getValidator()));
    }

    public function testWithConstraint()
    {
        $constraint = new AllConstraint([$this->getConstraint()]);

        self::assertEquals([], $constraint->validate('traversable', ['string'], $this->getContext(), $this->getValidator()));
    }

    public function testWithConstraintAndError()
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
        /** @var ValidatorInterface|MockObject $validator */
        $validator = $this->getMockForInterface(ValidatorInterface::class);

        return $validator;
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
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockForInterface(ValidatorContextInterface::class);

        return $context;
    }
}
