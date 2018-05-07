<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\Constraint\AllConstraint;
use Chubbyphp\Validation\Constraint\DateConstraint;
use Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingBuilder;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\Validator\Constraints\BicValidator;
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
            private $name;

            /**
             * @var string
             */
            private $bic;

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
            public function getName(): string
            {
                return $this->name;
            }

            /**
             * @param string $name
             *
             * @return self
             */
            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }

            /**
             * @return string
             */
            public function getBic(): string
            {
                return $this->bic;
            }

            /**
             * @param string $bic
             *
             * @return self
             */
            public function setBic(string $bic): self
            {
                $this->bic = $bic;

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

        $object->setName('');
        $object->setBic('invalid-bic');
        $object->setCallback('callback');
        $object->setAll(new \ArrayIterator(['31.01.2018', '29.02.2018']));

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
                        ValidationPropertyMappingBuilder::create('name',
                            [
                                new ConstraintAdapter(new NotBlank(), new NotBlankValidator()),
                            ])->getMapping(),
                        ValidationPropertyMappingBuilder::create('bic',
                            [
                                new ConstraintAdapter(new Bic(), new BicValidator()),
                            ])->getMapping(),
                        ValidationPropertyMappingBuilder::create('callback',
                            [
                                new ConstraintAdapter(
                                    new Callback([
                                        'payload' => ['key' => 'value'],
                                        'callback' => function (
                                            $object,
                                            ExecutionContextInterface $context,
                                            $payload = []
                                        ) {
                                            if ('callback' === $object) {
                                                $context->addViolation('callback', $payload);
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
                                    new DateConstraint(),
                                ]),
                            ])->getMapping(),
                    ];
                }
            },
        ]);

        $validator = new Validator($validatorObjectMappingRegistry);

        $errors = $validator->validate($object);

        self::assertEquals([
            new Error('name', 'This value should not be blank.', [
                'parameters' => [
                    '{{ value }}' => '""',
                ],
                'plural' => null,
                'invalidValue' => null,
                'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                'cause' => null,
            ]),
            new Error('bic', 'This is not a valid Business Identifier Code (BIC).', [
                'parameters' => [
                    '{{ value }}' => '"invalid-bic"',
                ],
                'plural' => null,
                'invalidValue' => null,
                'code' => 'f424c529-7add-4417-8f2d-4b656e4833e2',
                'cause' => null,
            ]),
            new Error('callback', 'callback', [
                'parameters' => [
                    'key' => 'value',
                ],
                'plural' => null,
                'invalidValue' => null,
                'code' => null,
                'cause' => null,
            ]),
        ], $errors);
    }
}
