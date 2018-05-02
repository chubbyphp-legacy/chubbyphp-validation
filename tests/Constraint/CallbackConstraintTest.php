<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\CallbackConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\CallbackConstraint
 */
final class CallbackConstraintTest extends TestCase
{
    public function testWithNullValue()
    {
        $constraint = new CallbackConstraint($this->getCallback());

        self::assertEquals([], $constraint->validate('callback', null, $this->getContext()));
    }

    public function testWithOtherValue()
    {
        $constraint = new CallbackConstraint($this->getCallback());

        self::assertEquals([
            new Error('callback', 'constrain.callback', ['value' => 'value']),
        ], $constraint->validate('callback', 'value', $this->getContext()));
    }

    /**
     * @return callable
     */
    private function getCallback(): callable
    {
        return function (
            string $path,
            $value,
            ValidatorContextInterface $context,
            ValidatorInterface $validator = null
        ) {
            if (null === $value) {
                return [];
            }

            return [
                new Error($path, 'constrain.callback', ['value' => $value]),
            ];
        };
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
