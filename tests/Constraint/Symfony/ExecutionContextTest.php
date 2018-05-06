<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Constraint\Symfony\ExecutionContext;
use Chubbyphp\Validation\Constraint\Symfony\NotImplementedException;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * @covers \Chubbyphp\Validation\Constraint\Symfony\ExecutionContext
 */
final class ExecutionContextTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testAddViolation()
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

    public function testBuildViolation()
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

    public function testGetValidator()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::getValidator" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->getValidator();
    }

    public function testGetObject()
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertNull($context->getObject());
    }

    public function testSetNode()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::setNode" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->setNode('value', new \stdClass(), null, 'path[0].property');
    }

    public function testSetGroup()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::setGroup" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->setGroup('group');
    }

    public function testSetConstraint()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::setConstraint" is not implemented');

        /** @var Constraint|MockObject $constraint */
        $constraint = $this->getMockBuilder(Constraint::class)->disableOriginalConstructor()->getMockForAbstractClass();

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->setConstraint($constraint);
    }

    public function testMarkGroupAsValidated()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::markGroupAsValidated" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->markGroupAsValidated('cacheKey', 'groupHasn');
    }

    public function testIsGroupValidated()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::isGroupValidated" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->isGroupValidated('cacheKey', 'groupHasn');
    }

    public function testMarkConstraintAsValidated()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::markConstraintAsValidated" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->markConstraintAsValidated('cacheKey', 'constraintHash');
    }

    public function testIsConstraintValidated()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::isConstraintValidated" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->isConstraintValidated('cacheKey', 'constraintHash');
    }

    public function testMarkObjectAsInitialized()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::markObjectAsInitialized" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->markObjectAsInitialized('cacheKey');
    }

    public function testIsObjectInitialize()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::isObjectInitialized" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->isObjectInitialized('cacheKey');
    }

    public function testGetRoot()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::getRoot" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->getRoot();
    }

    public function testGetValue()
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertSame('value', $context->getValue());
    }

    public function testGetMetadata()
    {
        self::expectException(NotImplementedException::class);
        self::expectExceptionMessage('Method "Chubbyphp\Validation\Constraint\Symfony\ExecutionContext::getMetadata" is not implemented');

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());
        $context->getMetadata();
    }

    public function testGetGroup()
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext([
            'getGroups' => [
                [
                    'return' => [],
                ],
            ],
        ]));

        self::assertSame('Default', $context->getGroup());

        $context = new ExecutionContext('path[0].property', 'value', $this->getContext([
            'getGroups' => [
                [
                    'return' => ['group1', 'group2'],
                ],
            ],
        ]));

        self::assertSame('group1', $context->getGroup());
    }

    public function testGetClassName()
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertNull($context->getClassName());
    }

    public function testGetPropertyName()
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertSame('property', $context->getPropertyName());
    }

    public function testGetPropertyPath()
    {
        $context = new ExecutionContext('path[0].property', 'value', $this->getContext());

        self::assertSame('path[0].property.subPath', $context->getPropertyPath('subPath'));
    }

    public function testGetErrors()
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

    /**
     * @param array $methods
     *
     * @return ValidatorContextInterface
     */
    private function getContext(array $methods = []): ValidatorContextInterface
    {
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockForInterface(ValidatorContextInterface::class, $methods);

        return $context;
    }
}
