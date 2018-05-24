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
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ValidatorTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testValidateWithoutContext()
    {
        $model = $this->getModel();

        $classError = $this->getMockForInterface(
            ErrorInterface::class,
            [
                'getKey' => [
                    ['return' => 'key'],
                ],
                'getArguments' => [
                    ['return' => ['key' => 'value']],
                ],
            ]
        );

        $classConstraint = $this->getMockForInterface(
            ConstraintInterface::class,
            [
                'validate' => [
                    ['return' => [$classError]],
                ],
            ]
        );

        $classMapping = $this->getMockForInterface(
            ValidationClassMappingInterface::class,
            [
                'getConstraints' => [
                    ['arguments' => [], 'return' => [$classConstraint]],
                ],
                'getGroups' => [],
            ]
        );

        $propertyError = $this->getMockForInterface(
            ErrorInterface::class,
            [
                'getKey' => [
                    ['return' => 'key'],
                ],
                'getArguments' => [
                    ['return' => ['key' => 'value']],
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
                    ['arguments' => [get_class($model)], 'return' => $mapping],
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
