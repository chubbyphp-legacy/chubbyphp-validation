<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\Symfony\ConstraintViolationBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @covers \Chubbyphp\Validation\Constraint\Symfony\ConstraintViolationBuilder
 *
 * @internal
 */
final class ConstraintViolationBuilderTest extends TestCase
{
    public function testWithDefaults(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path');
        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testWithAtPath(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = (new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path'))
            ->atPath('changedPath')
        ;

        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('changedPath', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testWithSetParameter(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = (new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path'))
            ->setParameter('key', 'anotherValue')
            ->setParameter('anotherKey', 'value')
        ;

        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'anotherValue', 'anotherKey' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testWithSetParameters(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = (new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path'))
            ->setParameters(['anotherKey' => 'anotherValue'])
        ;

        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['anotherKey' => 'anotherValue'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testWithSetTranslationDomain(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = (new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path'))
            ->setTranslationDomain('de')
        ;

        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testWithSetInvalidValue(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = (new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path'))
            ->setInvalidValue('invalidValue')
        ;

        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path', $violation->getPropertyPath());
        self::assertSame('invalidValue', $violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testWithSetPlural(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = (new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path'))
            ->setPlural(3)
        ;

        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertSame(3, $violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testWithSetCode(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = (new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path'))
            ->setCode('67151038-95d4-4e63-b0af-3b42eb9d80e4')
        ;

        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertSame('67151038-95d4-4e63-b0af-3b42eb9d80e4', $violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testWithSetCause(): void
    {
        $violations = new ConstraintViolationList();

        $violationBuilder = (new ConstraintViolationBuilder($violations, 'message', ['key' => 'value'], 'path'))
            ->setCause('cause')
        ;

        $violationBuilder->addViolation();

        self::assertSame(1, $violations->count());

        /** @var ConstraintViolation $violation */
        $violation = $violations->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertSame('cause', $violation->getCause());
    }
}
