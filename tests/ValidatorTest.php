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

    public function testValidateMissingMapping()
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

    public function testValidateWithoutContext()
    {
        $model = $this->getModel();
        $class = get_class($model);

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

        $classConstraint = $this->getMockForInterface(
            ConstraintInterface::class,
            [
                'validate' => [
                    Call::create()->setReturn([$classError]),
                ],
            ]
        );

        $classMapping = $this->getMockForInterface(
            ValidationClassMappingInterface::class,
            [
                'getConstraints' => [
                    Call::create()->setArguments([])->setReturn([$classConstraint]),
                ],
                'getGroups' => [],
            ]
        );

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

        $propertyConstraint = $this->getMockForInterface(
            ConstraintInterface::class,
            [
                'validate' => [
                    ['return' => [$propertyError]],
                ],
            ]
        );

        $propertyMapping = $this->getMockForInterface(
            ValidationPropertyMappingInterface::class,
            [
                'getName' => [
                    ['arguments' => [], 'return' => 'name'],
                ],
                'getConstraints' => [
                    ['arguments' => [], 'return' => [$propertyConstraint]],
                ],
                'getGroups' => [],
            ]
        );

        $mapping = $this->getMockForInterface(
            ValidationMappingProviderInterface::class,
            [
                'getValidationClassMapping' => [
                    ['arguments' => [''], 'return' => $classMapping],
                ],
                'getValidationPropertyMappings' => [
                    ['arguments' => [''], 'return' => [$propertyMapping]],
                ],
            ]
        );

        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->getMockForInterface(
            ValidationMappingProviderRegistryInterface::class,
            [
                'provideMapping' => [
                    ['arguments' => [$class], 'return' => $mapping],
                ],
            ]
        );

        $logger = $this->getMockForInterface(
            LoggerInterface::class,
            [
                'info' => [
                    ['arguments' => [
                        'deserialize: path {path}',
                        ['path' => ''],
                    ]],
                    ['arguments' => [
                        'deserialize: path {path}',
                        ['path' => 'name'],
                    ]],
                ],
                'debug' => [
                    ['arguments' => [
                        'deserialize: path {path}, constraint {constraint}',
                        ['path' => '', 'constraint' => get_class($classConstraint)],
                    ]],
                    ['arguments' => [
                        'deserialize: path {path}, constraint {constraint}',
                        ['path' => 'name', 'constraint' => get_class($propertyConstraint)],
                    ]],
                ],
                'notice' => [
                    ['arguments' => [
                        'deserialize: path {path}, constraint {constraint}, error {error}',
                        [
                            'path' => '',
                            'constraint' => get_class($classConstraint),
                            'error' => ['key' => 'key', 'arguments' => ['key' => 'value']],
                        ],
                    ]],
                    ['arguments' => [
                        'deserialize: path {path}, constraint {constraint}, error {error}',
                        [
                            'path' => 'name',
                            'constraint' => get_class($propertyConstraint),
                            'error' => ['key' => 'key', 'arguments' => ['key' => 'value']],
                        ],
                    ]],
                ],
            ]
        );

        $validator = new Validator($validationMappingProviderRegistry, $logger);

        $errors = $validator->validate($model);

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
