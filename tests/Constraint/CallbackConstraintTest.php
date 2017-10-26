<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\CallbackConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\CallbackConstraint
 */
final class CallbackConstraintTest extends TestCase
{
    public function testWithNullValue()
    {
        $constraint = new CallbackConstraint($this->getCallback());

        self::assertEquals([], $constraint->validate('callback', null));
    }

    public function testWithOtherValue()
    {
        $constraint = new CallbackConstraint($this->getCallback());

        self::assertEquals([
            new Error('callback', 'constrain.callback', ['input' => 'value']),
        ], $constraint->validate('callback', 'value'));
    }

    /**
     * @return callable
     */
    private function getCallback(): callable
    {
        return function (string $path, $input, ValidatorInterface $validator = null) {
            if (null === $input) {
                return [];
            }

            return [
                new Error($path, 'constrain.callback', ['input' => $input]),
            ];
        };
    }
}
