<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\Constraint\AllConstraint;
use Chubbyphp\Validation\Constraint\DateTimeConstraint;
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
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotNullValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ValidatorTest extends TestCase
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
                        ValidationPropertyMappingBuilder::create('notBlank', [
                            new ConstraintAdapter(new NotBlank(), new NotBlankValidator()),
                        ])->getMapping(),
                        ValidationPropertyMappingBuilder::create('numeric', [new NumericRangeConstraint(6)])
                            ->getMapping(),
                        ValidationPropertyMappingBuilder::create('callback', [
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
                        ValidationPropertyMappingBuilder::create('all', [
                            new AllConstraint([
                                new ConstraintAdapter(new NotNull(), new NotNullValidator()),
                                new DateTimeConstraint(),
                            ]),
                        ])->getMapping(),
                    ];
                }
            },
        ]);

        $validator = new Validator($validatorObjectMappingRegistry);

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
            new Error('all[1]', 'constraint.date.error', [
                'message' => 'Unexpected character',
                'positions' => [8, 9],
                'value' => '01.13.2018',
            ]),
        ], $errors);
    }
}
