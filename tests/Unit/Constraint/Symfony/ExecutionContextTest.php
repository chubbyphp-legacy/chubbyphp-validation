<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint\Symfony;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\Symfony\ExecutionContext;
use Chubbyphp\Validation\Constraint\Symfony\NotImplementedException;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * @covers \Chubbyphp\Validation\Constraint\Symfony\ExecutionContext
 *
 * @internal
 */
final class ExecutionContextTest extends TestCase
{
    use MockByCallsTrait;

    public function testAddViolation(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->addViolation('message', ['key' => 'value']);

        self::assertSame(1, $context->getViolations()->count());

        /** @var ConstraintViolation $violation */
        $violation = $context->getViolations()->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path[0].property', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testBuildViolation(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        $violationBuilder = $context->buildViolation('message', ['key' => 'value']);
        $violationBuilder->addViolation();

        self::assertSame(1, $context->getViolations()->count());

        /** @var ConstraintViolation $violation */
        $violation = $context->getViolations()->get(0);

        self::assertInstanceOf(ConstraintViolation::class, $violation);

        self::assertSame('message', $violation->getMessage());
        self::assertSame('message', $violation->getMessageTemplate());
        self::assertSame(['key' => 'value'], $violation->getParameters());
        self::assertNull($violation->getPlural());
        self::assertNull($violation->getRoot());
        self::assertSame('path[0].property', $violation->getPropertyPath());
        self::assertNull($violation->getInvalidValue());
        self::assertNull($violation->getCode());
        self::assertNull($violation->getCause());
    }

    public function testGetValidator(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::getValidator" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->getValidator();
    }

    public function testGetObject(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertNull($context->getObject());
    }

    public function testSetNode(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::setNode" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->setNode('value', new \stdClass(), null, 'path[0].property');
    }

    public function testSetGroup(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::setGroup" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->setGroup('group');
    }

    public function testSetConstraint(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::setConstraint" is not implemented');

        /** @var Constraint|MockObject $constraint */
        $constraint = $this->getMockByCalls(Constraint::class);

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->setConstraint($constraint);
    }

    public function testMarkGroupAsValidated(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::markGroupAsValidated" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->markGroupAsValidated('cacheKey', 'groupHasn');
    }

    public function testIsGroupValidated(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::isGroupValidated" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->isGroupValidated('cacheKey', 'groupHasn');
    }

    public function testMarkConstraintAsValidated(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::markConstraintAsValidated" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->markConstraintAsValidated('cacheKey', 'constraintHash');
    }

    public function testIsConstraintValidated(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::isConstraintValidated" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->isConstraintValidated('cacheKey', 'constraintHash');
    }

    public function testMarkObjectAsInitialized(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::markObjectAsInitialized" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->markObjectAsInitialized('cacheKey');
    }

    public function testIsObjectInitialize(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::isObjectInitialized" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->isObjectInitialized('cacheKey');
    }

    public function testGetRoot(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::getRoot" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->getRoot();
    }

    public function testGetValue(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertSame('value', $context->getValue());
    }

    public function testGetMetadata(): void
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::getMetadata" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->getMetadata();
    }

    public function testGetGroup(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext([
            Call::create('getGroups')->with()->willReturn([]),
        ]));

        self::assertSame('Default', $context->getGroup());

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext([
            Call::create('getGroups')->with()->willReturn(['group1', 'group2']),
        ]));

        self::assertSame('group1', $context->getGroup());
    }

    public function testGetClassName(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertNull($context->getClassName());
    }

    public function testGetPropertyName(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertSame('property', $context->getPropertyName());
    }

    public function testGetPropertyPath(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertSame('path[0].property.subPath', $context->getPropertyPath('subPath'));
    }

    public function testGetErrors(): void
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->addViolation('message', ['key' => 'value']);

        $errors = $context->getErrors();

        self::assertCount(1, $errors);

        /** @var ErrorInterface $error */
        $error = $errors[0];

        self::assertInstanceOf(ErrorInterface::class, $error);

        self::assertSame('path[0].property', $error->getPath());
        self::assertSame('message', $error->getKey());
        self::assertSame([
            'parameters' => [
                'key' => 'value',
            ],
            'plural' => null,
            'invalidValue' => null,
            'code' => null,
            'cause' => null,
        ], $error->getArguments());
    }

    private function getContext(array $methods = []): ValidatorContextInterface
    {
        /* @var ValidatorContextInterface|MockObject $context */
        return $this->getMockByCalls(ValidatorContextInterface::class, $methods);
    }
}
