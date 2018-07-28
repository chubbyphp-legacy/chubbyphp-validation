<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\Argument\ArgumentInstanceOf;
use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @covers \Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter
 */
final class ConstraintAdapterTest extends TestCase
{
    use MockByCallsTrait;

    public function testValidate()
    {
        /** @var Constraint|MockObject $constraint */
        $constraint = $this->getMockByCalls(Constraint::class);

        /** @var ConstraintValidatorInterface|MockObject $constraintValidator */
        $constraintValidator = $this->getMockByCalls(ConstraintValidatorInterface::class, [
            Call::create('initialize')->with(new ArgumentInstanceOf(ExecutionContextInterface::class)),
            Call::create('validate')->with('test', $constraint),
        ]);

        $constraintAdapter = new ConstraintAdapter($constraint, $constraintValidator);

        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        self::assertSame([], $constraintAdapter->validate('path[0].property', 'test', $context));
    }
}
