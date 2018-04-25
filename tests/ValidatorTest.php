<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter;
use Chubbyphp\Validation\Mapping\ValidationFieldMappingBuilder;
use Chubbyphp\Validation\Mapping\ValidationFieldMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationObjectMappingInterface;
use Chubbyphp\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\Validator\Constraints\BicValidator;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotBlankValidator;
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
        };

        $object->setName('');
        $object->setBic('invalid-bic');
        $object->setCallback('callback');

        $validatorObjectMappingRegistry = new Validator\ValidatorObjectMappingRegistry([
            new class($object) implements ValidationObjectMappingInterface {
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
                 * @param string      $path
                 * @param string|null $type
                 *
                 * @return ValidationFieldMappingInterface[]
                 */
                public function getValidationFieldMappings(string $path, string $type = null): array
                {
                    $callback = new Callback();
                    $callback->payload = ['key' => 'value'];
                    $callback->callback = function ($object, ExecutionContextInterface $context, $payload) {
                        if ('callback' === $object) {
                            $context->addViolation('callback', $payload);
                        }
                    };

                    return [
                        ValidationFieldMappingBuilder::create('name', [
                            new ConstraintAdapter(new NotBlank(), new NotBlankValidator()),
                        ])->getMapping(),
                        ValidationFieldMappingBuilder::create('bic', [
                            new ConstraintAdapter(new Bic(), new BicValidator()),
                        ])->getMapping(),
                        ValidationFieldMappingBuilder::create('callback', [
                            new ConstraintAdapter($callback, new CallbackValidator()),
                        ])->getMapping(),
                    ];
                }
            },
        ]);

        $validator = new Validator($validatorObjectMappingRegistry);

        $errors = $validator->validate($object);

        var_dump($errors);
    }
}
