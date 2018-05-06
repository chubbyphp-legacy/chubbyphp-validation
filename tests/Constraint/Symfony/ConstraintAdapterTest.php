<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;

/**
 * @covers \Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter
 */
final class ConstraintAdapterTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testValidate()
    {
        /** @var Constraint|MockObject $constraint */
        $constraint = $this->getMockBuilder(Constraint::class)->disableOriginalConstructor()->getMock();

        /** @var ConstraintValidatorInterface|MockObject $constraintValidator */
        $constraintValidator = $this->getMockForInterface(ConstraintValidatorInterface::class, [
            'initialize' => [
                [],
            ],
            'validate' => [
                [
                    'arguments' => ['test', $constraint],
                ],
            ],
        ]);

        $constraintAdapter = new ConstraintAdapter($constraint, $constraintValidator);

        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockForInterface(ValidatorContextInterface::class);

        self::assertSame([], $constraintAdapter->validate('path[0].property', 'test', $context));
    }
}
