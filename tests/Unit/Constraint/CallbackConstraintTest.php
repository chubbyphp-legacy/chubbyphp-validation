<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\CallbackConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\CallbackConstraint
 *
 * @internal
 */
final class CallbackConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new CallbackConstraint($this->getCallback());

        self::assertEquals([], $constraint->validate('callback', null, $this->getContext()));
    }

    public function testWithOtherValue(): void
    {
        $constraint = new CallbackConstraint($this->getCallback());

        self::assertEquals([
            new Error('callback', 'constrain.callback', ['value' => 'value']),
        ], $constraint->validate('callback', 'value', $this->getContext()));
    }

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

    private function getContext(): ValidatorContextInterface
    {
        /* @var ValidatorContextInterface|MockObject $context */
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
