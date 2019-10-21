<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit;

use Chubbyphp\Mock\Argument\ArgumentInstanceOf;
use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use Chubbyphp\Validation\Validator;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * @covers \Chubbyphp\Validation\Validator
 */
final class ValidatorTest extends TestCase
{
    use MockByCallsTrait;

    public function testValidateMissingMappingExpectValidatorLogicException(): void
    {
        $model = $this->getModel();
        $class = get_class($model);

        $exceptionMessage = sprintf('There is no mapping for class: "%s"', $class);

        self::expectException(ValidatorLogicException::class);
        self::expectExceptionMessage($exceptionMessage);

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockByCalls(ValidationMappingProviderRegistryInterface::class, [
            Call::create('provideMapping')
                ->with($class)
                ->willThrowException(ValidatorLogicException::createMissingMapping($class)),
        ]);

        /** @var LoggerInterface $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class, [
            Call::create('error')->with('validate: {exception}', ['exception' => $exceptionMessage]),
        ]);

        $validator = new Validator($validationMappingProviderRegistry, $logger);
        $validator->validate($model);
    }

    public function testValidateWithoutClassMappingAndWithoutPropertyMapping(): void
    {
        $model = $this->getModel();
        $class = get_class($model);

        /** @var ValidationMappingProviderInterface $mapping */
        $mapping = $this->getMockByCalls(ValidationMappingProviderInterface::class, [
            Call::create('getValidationClassMapping')->with('')->willReturn(null),
            Call::create('getValidationPropertyMappings')->with('')->willReturn([]),
        ]);

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockByCalls(ValidationMappingProviderRegistryInterface::class, [
            Call::create('provideMapping')->with($class)->willReturn($mapping),
        ]);

        /** @var LoggerInterface $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class, []);

        $validator = new Validator($validationMappingProviderRegistry, $logger);

        self::assertCount(0, $validator->validate($model));
    }

    public function testValidateWithClassMappingAndWithPropertyMapping(): void
    {
        $model = $this->getModel();
        $class = get_class($model);

        /** @var ErrorInterface $classError */
        $classError = $this->getMockByCalls(ErrorInterface::class, [
            Call::create('getKey')->with()->willReturn('key'),
            Call::create('getArguments')->with()->willReturn(['key' => 'value']),
        ]);

        /** @var ConstraintInterface $classConstraint */
        $classConstraint = $this->getMockByCalls(ConstraintInterface::class, [
            Call::create('validate')
                ->with(
                    '',
                    $model,
                    new ArgumentInstanceOf(ValidatorContextInterface::class),
                    new ArgumentInstanceOf(ValidatorInterface::class)
                )
                ->willReturn([$classError]),
        ]);

        /** @var ValidationClassMappingInterface $classMapping */
        $classMapping = $this->getMockByCalls(ValidationClassMappingInterface::class, [
            Call::create('getConstraints')->with()->willReturn([$classConstraint]),
        ]);

        /** @var ErrorInterface $propertyError */
        $propertyError = $this->getMockByCalls(ErrorInterface::class, [
            Call::create('getKey')->with()->willReturn('key'),
            Call::create('getArguments')->with()->willReturn(['key' => 'value']),
        ]);

        /** @var AccessorInterface $propertyAccessor */
        $propertyAccessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($model)->willReturn('value'),
        ]);

        /** @var ConstraintInterface $propertyConstraint */
        $propertyConstraint = $this->getMockByCalls(ConstraintInterface::class, [
            Call::create('validate')
                ->with(
                    'name',
                    'value',
                    new ArgumentInstanceOf(ValidatorContextInterface::class),
                    new ArgumentInstanceOf(ValidatorInterface::class)
                )
                ->willReturn([$propertyError]),
        ]);

        /** @var ValidationPropertyMappingInterface $propertyMapping */
        $propertyMapping = $this->getMockByCalls(ValidationPropertyMappingInterface::class, [
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getAccessor')->with()->willReturn($propertyAccessor),
            Call::create('getConstraints')->with()->willReturn([$propertyConstraint]),
        ]);

        /** @var ValidationMappingProviderInterface $mapping */
        $mapping = $this->getMockByCalls(ValidationMappingProviderInterface::class, [
            Call::create('getValidationClassMapping')->with('')->willReturn($classMapping),
            Call::create('getValidationPropertyMappings')->with('')->willReturn([$propertyMapping]),
        ]);

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockByCalls(ValidationMappingProviderRegistryInterface::class, [
            Call::create('provideMapping')->with($class)->willReturn($mapping),
        ]);

        /** @var LoggerInterface $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class, [
            Call::create('info')->with('validate: path {path}', ['path' => '']),
            Call::create('debug')->with(
                'validate: path {path}, constraint {constraint}',
                ['path' => '', 'constraint' => get_class($classConstraint)]
            ),
            Call::create('notice')->with(
                'validate: path {path}, constraint {constraint}, error {error}',
                [
                    'path' => '',
                    'constraint' => get_class($classConstraint),
                    'error' => ['key' => 'key', 'arguments' => ['key' => 'value']],
                ]
            ),
            Call::create('info')->with('validate: path {path}', ['path' => 'name']),
            Call::create('debug')->with(
                'validate: path {path}, constraint {constraint}',
                ['path' => 'name', 'constraint' => get_class($propertyConstraint)]
            ),
            Call::create('notice')->with(
                'validate: path {path}, constraint {constraint}, error {error}',
                [
                    'path' => 'name',
                    'constraint' => get_class($propertyConstraint),
                    'error' => ['key' => 'key', 'arguments' => ['key' => 'value']],
                ]
            ),
        ]);

        $validator = new Validator($validationMappingProviderRegistry, $logger);

        $errors = $validator->validate($model);

        self::assertCount(2, $errors);

        self::assertEquals($classError, $errors[0]);
        self::assertEquals($propertyError, $errors[1]);
    }

    public function testValidateWithClassMappingAndWithPropertyMappingWithoutUsedGroup(): void
    {
        $model = $this->getModel();
        $class = get_class($model);

        /** @var ValidationClassMappingInterface $classMapping */
        $classMapping = $this->getMockByCalls(ValidationClassMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
        ]);

        /** @var ValidationPropertyMappingInterface $propertyMapping */
        $propertyMapping = $this->getMockByCalls(ValidationPropertyMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1']),
        ]);

        /** @var ValidationMappingProviderInterface $mapping */
        $mapping = $this->getMockByCalls(ValidationMappingProviderInterface::class, [
            Call::create('getValidationClassMapping')->with('')->willReturn($classMapping),
            Call::create('getValidationPropertyMappings')->with('')->willReturn([$propertyMapping]),
        ]);

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockByCalls(ValidationMappingProviderRegistryInterface::class, [
            Call::create('provideMapping')->with($class)->willReturn($mapping),
        ]);

        /** @var LoggerInterface $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class);

        $context = $this->getMockByCalls(ValidatorContextInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group2']),
            Call::create('getGroups')->with()->willReturn(['group2']),
        ]);

        $validator = new Validator($validationMappingProviderRegistry, $logger);

        self::assertCount(0, $validator->validate($model, $context));
    }

    public function testValidateWithClassMappingAndWithPropertyMappingWithUsedGroup(): void
    {
        $model = $this->getModel();
        $class = get_class($model);

        /** @var ErrorInterface $classError */
        $classError = $this->getMockByCalls(ErrorInterface::class, [
            Call::create('getKey')->with()->willReturn('key'),
            Call::create('getArguments')->with()->willReturn(['key' => 'value']),
        ]);

        /** @var ConstraintInterface $classConstraint */
        $classConstraint = $this->getMockByCalls(ConstraintInterface::class, [
            Call::create('validate')
                ->with(
                    '',
                    $model,
                    new ArgumentInstanceOf(ValidatorContextInterface::class),
                    new ArgumentInstanceOf(ValidatorInterface::class)
                )
                ->willReturn([$classError]),
        ]);

        /** @var ValidationClassMappingInterface $classMapping */
        $classMapping = $this->getMockByCalls(ValidationClassMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1', 'group2']),
            Call::create('getConstraints')->with()->willReturn([$classConstraint]),
        ]);

        /** @var ErrorInterface $propertyError */
        $propertyError = $this->getMockByCalls(ErrorInterface::class, [
            Call::create('getKey')->with()->willReturn('key'),
            Call::create('getArguments')->with()->willReturn(['key' => 'value']),
        ]);

        /** @var AccessorInterface $propertyAccessor */
        $propertyAccessor = $this->getMockByCalls(AccessorInterface::class, [
            Call::create('getValue')->with($model)->willReturn('value'),
        ]);

        /** @var ConstraintInterface $propertyConstraint */
        $propertyConstraint = $this->getMockByCalls(ConstraintInterface::class, [
            Call::create('validate')
                ->with(
                    'name',
                    'value',
                    new ArgumentInstanceOf(ValidatorContextInterface::class),
                    new ArgumentInstanceOf(ValidatorInterface::class)
                )
                ->willReturn([$propertyError]),
        ]);

        /** @var ValidationPropertyMappingInterface $propertyMapping */
        $propertyMapping = $this->getMockByCalls(ValidationPropertyMappingInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group1', 'group2']),
            Call::create('getName')->with()->willReturn('name'),
            Call::create('getAccessor')->with()->willReturn($propertyAccessor),
            Call::create('getConstraints')->with()->willReturn([$propertyConstraint]),
        ]);

        /** @var ValidationMappingProviderInterface $mapping */
        $mapping = $this->getMockByCalls(ValidationMappingProviderInterface::class, [
            Call::create('getValidationClassMapping')->with('')->willReturn($classMapping),
            Call::create('getValidationPropertyMappings')->with('')->willReturn([$propertyMapping]),
        ]);

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockByCalls(ValidationMappingProviderRegistryInterface::class, [
            Call::create('provideMapping')->with($class)->willReturn($mapping),
        ]);

        /** @var LoggerInterface $logger */
        $logger = $this->getMockByCalls(LoggerInterface::class, [
            Call::create('info')->with('validate: path {path}', ['path' => '']),
            Call::create('debug')->with(
                'validate: path {path}, constraint {constraint}',
                ['path' => '', 'constraint' => get_class($classConstraint)]
            ),
            Call::create('notice')->with(
                'validate: path {path}, constraint {constraint}, error {error}',
                [
                    'path' => '',
                    'constraint' => get_class($classConstraint),
                    'error' => ['key' => 'key', 'arguments' => ['key' => 'value']],
                ]
            ),
            Call::create('info')->with('validate: path {path}', ['path' => 'name']),
            Call::create('debug')->with(
                'validate: path {path}, constraint {constraint}',
                ['path' => 'name', 'constraint' => get_class($propertyConstraint)]
            ),
            Call::create('notice')->with(
                'validate: path {path}, constraint {constraint}, error {error}',
                [
                    'path' => 'name',
                    'constraint' => get_class($propertyConstraint),
                    'error' => ['key' => 'key', 'arguments' => ['key' => 'value']],
                ]
            ),
        ]);

        $context = $this->getMockByCalls(ValidatorContextInterface::class, [
            Call::create('getGroups')->with()->willReturn(['group2']),
            Call::create('getGroups')->with()->willReturn(['group2']),
        ]);

        $validator = new Validator($validationMappingProviderRegistry, $logger);

        $errors = $validator->validate($model, $context);

        self::assertCount(2, $errors);

        self::assertEquals($classError, $errors[0]);
        self::assertEquals($propertyError, $errors[1]);
    }

    private function getModel()
    {
        return new class() {
        };
    }
}
