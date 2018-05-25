<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use Chubbyphp\Validation\Validator;
use Chubbyphp\Validation\ValidatorLogicException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ValidatorTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testValidateMissingMappingExpectValidatorLogicException()
    {
        $model = $this->getModel();
        $class = get_class($model);

        $exceptionMessage = sprintf('There is no mapping for class: "%s"', $class);

        self::expectException(ValidatorLogicException::class);
        self::expectExceptionMessage($exceptionMessage);

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockForInterface(
            ValidationMappingProviderRegistryInterface::class,
            [
                'provideMapping' => [
                    Call::create()
                        ->setArguments([$class])
                        ->setException(ValidatorLogicException::createMissingMapping($class)),
                ],
            ]
        );

        /** @var LoggerInterface $logger */
        $logger = $this->getMockForInterface(
            LoggerInterface::class,
            [
                'error' => [
                    Call::create()->setArguments(['validate: {exception}', ['exception' => $exceptionMessage]]),
                ],
            ]
        );

        $validator = new Validator($validationMappingProviderRegistry, $logger);
        $validator->validate($model);
    }

    public function testValidateWithClassMappingAndWithPropertyMappingWithoutContext()
    {
        $model = $this->getModel();
        $class = get_class($model);

        /** @var ErrorInterface $classError */
        $classError = $this->getMockForInterface(
            ErrorInterface::class,
            [
                'getKey' => [
                    Call::create()->setReturn('key'),
                ],
                'getArguments' => [
                    Call::create()->setReturn(['key' => 'value']),
                ],
            ]
        );

        /** @var ConstraintInterface $classConstraint */
        $classConstraint = $this->getMockForInterface(
            ConstraintInterface::class,
            [
                'validate' => [
                    Call::create()->setReturn([$classError]),
                ],
            ]
        );

        /** @var ValidationClassMappingInterface $classMapping */
        $classMapping = $this->getMockForInterface(
            ValidationClassMappingInterface::class,
            [
                'getConstraints' => [
                    Call::create()->setArguments([])->setReturn([$classConstraint]),
                ],
            ]
        );

        /** @var ErrorInterface $propertyError */
        $propertyError = $this->getMockForInterface(
            ErrorInterface::class,
            [
                'getKey' => [
                    Call::create()->setReturn('key'),
                ],
                'getArguments' => [
                    Call::create()->setReturn(['key' => 'value']),
                ],
            ]
        );

        /** @var ConstraintInterface $propertyConstraint */
        $propertyConstraint = $this->getMockForInterface(
            ConstraintInterface::class,
            [
                'validate' => [
                    Call::create()->setReturn([$propertyError]),
                ],
            ]
        );

        /** @var ValidationPropertyMappingInterface $propertyMapping */
        $propertyMapping = $this->getMockForInterface(
            ValidationPropertyMappingInterface::class,
            [
                'getName' => [
                    Call::create()->setReturn('name'),
                ],
                'getConstraints' => [
                    Call::create()->setReturn([$propertyConstraint]),
                ],
            ]
        );

        /** @var ValidationMappingProviderInterface $mapping */
        $mapping = $this->getMockForInterface(
            ValidationMappingProviderInterface::class,
            [
                'getValidationClassMapping' => [
                    Call::create()->setArguments([''])->setReturn($classMapping),
                ],
                'getValidationPropertyMappings' => [
                    Call::create()->setArguments([''])->setReturn([$propertyMapping]),
                ],
            ]
        );

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockForInterface(
            ValidationMappingProviderRegistryInterface::class,
            [
                'provideMapping' => [
                    Call::create()->setArguments([$class])->setReturn($mapping),
                ],
            ]
        );

        /** @var LoggerInterface $logger */
        $logger = $this->getMockForInterface(
            LoggerInterface::class,
            [
                'info' => [
                    Call::create()->setArguments(['deserialize: path {path}', ['path' => '']]),
                    Call::create()->setArguments(['deserialize: path {path}', ['path' => 'name']]),
                ],
                'debug' => [
                    Call::create()->setArguments([
                        'deserialize: path {path}, constraint {constraint}',
                        ['path' => '', 'constraint' => get_class($classConstraint)],
                    ]),
                    Call::create()->setArguments([
                        'deserialize: path {path}, constraint {constraint}',
                        ['path' => 'name', 'constraint' => get_class($propertyConstraint)],
                    ]),
                ],
                'notice' => [
                    Call::create()->setArguments([
                        'deserialize: path {path}, constraint {constraint}, error {error}',
                        [
                            'path' => '',
                            'constraint' => get_class($classConstraint),
                            'error' => ['key' => 'key', 'arguments' => ['key' => 'value']],
                        ],
                    ]),
                    Call::create()->setArguments([
                        'deserialize: path {path}, constraint {constraint}, error {error}',
                        [
                            'path' => 'name',
                            'constraint' => get_class($propertyConstraint),
                            'error' => ['key' => 'key', 'arguments' => ['key' => 'value']],
                        ],
                    ]),
                ],
            ]
        );

        $validator = new Validator($validationMappingProviderRegistry, $logger);

        $errors = $validator->validate($model);

        self::assertCount(2, $errors);

        self::assertEquals($classError, $errors[0]);
        self::assertEquals($propertyError, $errors[1]);
    }

    public function testValidateWithoutClassMappingAndWithoutPropertyMappingWithoutContext()
    {
        $model = $this->getModel();
        $class = get_class($model);

        /** @var ValidationMappingProviderInterface $mapping */
        $mapping = $this->getMockForInterface(
            ValidationMappingProviderInterface::class,
            [
                'getValidationClassMapping' => [
                    Call::create()->setArguments([''])->setReturn(null),
                ],
                'getValidationPropertyMappings' => [
                    Call::create()->setArguments([''])->setReturn([]),
                ],
            ]
        );

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockForInterface(
            ValidationMappingProviderRegistryInterface::class,
            [
                'provideMapping' => [
                    Call::create()->setArguments([$class])->setReturn($mapping),
                ],
            ]
        );

        /** @var LoggerInterface $logger */
        $logger = $this->getMockForInterface(LoggerInterface::class, []);

        $validator = new Validator($validationMappingProviderRegistry, $logger);

        self::assertCount(0, $validator->validate($model));
    }

    private function getModel()
    {
        return new class() {
        };
    }
}
