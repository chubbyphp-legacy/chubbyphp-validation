<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\Constraint\AllConstraint;
use Chubbyphp\Validation\Constraint\DateTimeConstraint;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Constraint\NumericRangeConstraint;
use Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingBuilder;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotNullValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @coversNothing
 */
class ValidatorIntegrationTest extends TestCase
{
    public function testValidator()
    {
        $object = new class() {
            /**
             * @var string
             */
            private $notBlank;

            /**
             * @var int
             */
            private $numeric;

            /**
             * @var string
             */
            private $callback;

            /**
             * @var \ArrayIterator
             */
            private $all;

            /**
             * @return string
             */
            public function getNotBlank(): string
            {
                return $this->notBlank;
            }

            /**
             * @param string $notBlank
             *
             * @return self
             */
            public function setNotBlank(string $notBlank): self
            {
                $this->notBlank = $notBlank;

                return $this;
            }

            /**
             * @return int
             */
            public function getNumeric(): int
            {
                return $this->numeric;
            }

            /**
             * @param int|string $numeric
             *
             * @return self
             */
            public function setNumeric($numeric): self
            {
                $this->numeric = $numeric;

                return $this;
            }

            /**
             * @return string
             */
            public function getCallback(): string
            {
                return $this->callback;
            }

            /**
             * @param string $callback
             *
             * @return self
             */
            public function setCallback(string $callback): self
            {
                $this->callback = $callback;

                return $this;
            }

            /**
             * @return \ArrayIterator
             */
            public function getAll(): \ArrayIterator
            {
                return $this->all;
            }

            /**
             * @param \ArrayIterator $all
             *
             * @return self
             */
            public function setAll(\ArrayIterator $all): self
            {
                $this->all = $all;

                return $this;
            }
        };

        $object->setNotBlank('');
        $object->setNumeric(5);
        $object->setCallback('callback');
        $object->setAll(new \ArrayIterator(['31.01.2018', '01.13.2018']));

        $validatorObjectMappingRegistry = new Validator\ValidatorObjectMappingRegistry([
            new class($object) implements ValidationMappingProviderInterface {
                private $object;

                /**
                 * @param object $object
                 */
                public function __construct($object)
                {
                    $this->object = $object;
                }

                /**
                 * @return string
                 */
                public function getClass(): string
                {
                    return get_class($this->object);
                }

                /**
                 * @param string $path
                 *
                 * @return ValidationClassMappingInterface
                 */
                public function getValidationClassMapping(string $path): ValidationClassMappingInterface
                {
                    return ValidationClassMappingBuilder::create([])->getMapping();
                }

                /**
                 * @param string      $path
                 * @param string|null $type
                 *
                 * @return ValidationPropertyMappingInterface[]
                 */
                public function getValidationPropertyMappings(string $path, string $type = null): array
                {
                    return [
                        ValidationPropertyMappingBuilder::create('notBlank',
                            [
                                new ConstraintAdapter(new NotBlank(), new NotBlankValidator()),
                            ])->getMapping(),
                        ValidationPropertyMappingBuilder::create('numeric', [new NumericRangeConstraint(6)])
                            ->getMapping(),
                        ValidationPropertyMappingBuilder::create('callback',
                            [
                                new ConstraintAdapter(
                                    new Callback([
                                        'callback' => function ($object, ExecutionContextInterface $context) {
                                            if ('callback' === $object) {
                                                $context->addViolation('callback');
                                            }
                                        },
                                    ]),
                                    new CallbackValidator()
                                ),
                            ])->getMapping(),
                        ValidationPropertyMappingBuilder::create('all',
                            [
                                new AllConstraint([
                                    new ConstraintAdapter(new NotNull(), new NotNullValidator()),
                                    new NotBlankConstraint(),
                                    new DateTimeConstraint('d.m.Y'),
                                ]),
                            ])->getMapping(),
                    ];
                }
            },
        ]);

        $logger = new class() extends AbstractLogger {
            /**
             * @var array
             */
            private $logs;

            public function log($level, $message, array $context = [])
            {
                $this->logs[] = ['level' => $level, 'message' => $message, 'context' => $context];
            }

            /**
             * @return array
             */
            public function getLogs(): array
            {
                return $this->logs;
            }
        };

        $validator = new Validator($validatorObjectMappingRegistry, $logger);

        $errors = $validator->validate($object);

        $object->getNumeric();

        self::assertEquals([
            new Error('notBlank', 'This value should not be blank.', [
                'parameters' => [
                    '{{ value }}' => '""',
                ],
                'plural' => null,
                'invalidValue' => null,
                'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                'cause' => null,
            ]),
            new Error('numeric', 'constraint.numericrange.outofrange', [
                'value' => 5,
                'min' => 6,
                'max' => null,
            ]),
            new Error('callback', 'callback', [
                'parameters' => [],
                'plural' => null,
                'invalidValue' => null,
                'code' => null,
                'cause' => null,
            ]),
            new Error('all[1]', 'constraint.date.warning', [
                'message' => 'The parsed date was invalid',
                'format' => 'd.m.Y',
                'value' => '01.13.2018',
            ]),
        ], $errors);

        self::assertEquals([
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => '',
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'notBlank',
                ],
            ],
            [
                'level' => 'debug',
                'message' => 'deserialize: path {path}, constraint {constraint}',
                'context' => [
                    'path' => 'notBlank',
                    'constraint' => 'Chubbyphp\\Validation\\Constraint\\Symfony\\ConstraintAdapter',
                ],
            ],
            [
                'level' => 'notice',
                'message' => 'deserialize: path {path}, constraint {constraint}, error {error}',
                'context' => [
                    'path' => 'notBlank',
                    'constraint' => 'Chubbyphp\\Validation\\Constraint\\Symfony\\ConstraintAdapter',
                    'error' => [
                        'key' => 'This value should not be blank.',
                        'arguments' => [
                            'parameters' => [
                                '{{ value }}' => '""',
                            ],
                            'plural' => null,
                            'invalidValue' => null,
                            'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                            'cause' => null,
                        ],
                    ],
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'numeric',
                ],
            ],
            [
                'level' => 'debug',
                'message' => 'deserialize: path {path}, constraint {constraint}',
                'context' => [
                    'path' => 'numeric',
                    'constraint' => 'Chubbyphp\\Validation\\Constraint\\NumericRangeConstraint',
                ],
            ],
            [
                'level' => 'notice',
                'message' => 'deserialize: path {path}, constraint {constraint}, error {error}',
                'context' => [
                    'path' => 'numeric',
                    'constraint' => 'Chubbyphp\\Validation\\Constraint\\NumericRangeConstraint',
                    'error' => [
                        'key' => 'constraint.numericrange.outofrange',
                        'arguments' => [
                            'value' => 5,
                            'min' => 6,
                            'max' => null,
                        ],
                    ],
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'callback',
                ],
            ],
            [
                'level' => 'debug',
                'message' => 'deserialize: path {path}, constraint {constraint}',
                'context' => [
                    'path' => 'callback',
                    'constraint' => 'Chubbyphp\\Validation\\Constraint\\Symfony\\ConstraintAdapter',
                ],
            ],
            [
                'level' => 'notice',
                'message' => 'deserialize: path {path}, constraint {constraint}, error {error}',
                'context' => [
                    'path' => 'callback',
                    'constraint' => 'Chubbyphp\\Validation\\Constraint\\Symfony\\ConstraintAdapter',
                    'error' => [
                        'key' => 'callback',
                        'arguments' => [
                            'parameters' => [
                            ],
                            'plural' => null,
                            'invalidValue' => null,
                            'code' => null,
                            'cause' => null,
                        ],
                    ],
                ],
            ],
            [
                'level' => 'info',
                'message' => 'deserialize: path {path}',
                'context' => [
                    'path' => 'all',
                ],
            ],
            [
                'level' => 'debug',
                'message' => 'deserialize: path {path}, constraint {constraint}',
                'context' => [
                    'path' => 'all',
                    'constraint' => 'Chubbyphp\\Validation\\Constraint\\AllConstraint',
                ],
            ],
            [
                'level' => 'notice',
                'message' => 'deserialize: path {path}, constraint {constraint}, error {error}',
                'context' => [
                    'path' => 'all',
                    'constraint' => 'Chubbyphp\\Validation\\Constraint\\AllConstraint',
                    'error' => [
                        'key' => 'constraint.date.warning',
                        'arguments' => [
                            'message' => 'The parsed date was invalid',
                            'format' => 'd.m.Y',
                            'value' => '01.13.2018',
                        ],
                    ],
                ],
            ],
        ], $logger->getLogs());
    }
}
