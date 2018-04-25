<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter;
use Chubbyphp\Validation\Mapping\ValidationFieldMappingBuilder;
use Chubbyphp\Validation\Mapping\ValidationFieldMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationObjectMappingInterface;
use Chubbyphp\Validation\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotNullValidator;

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
        };

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
                    return [
                        ValidationFieldMappingBuilder::create('name', [
                            new NotNullConstraint(),
                            new ConstraintAdapter(new NotNull(), new NotNullValidator()),
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
