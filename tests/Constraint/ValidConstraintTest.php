<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Tests\Validation\MockForInterfaceTrait;
use Chubbyphp\Validation\Constraint\ValidConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\ValidConstraint
 */
final class ValidConstraintTest extends TestCase
{
    use MockForInterfaceTrait;

    public function testWithNullValue()
    {
        $constraint = new ValidConstraint();

        self::assertEquals([], $constraint->validate('valid', null, $this->getContext()));
    }

    public function testWithMissingValidator()
    {
        self::expectException(ValidatorLogicException::class);
        self::expectExceptionMessage('There is no validator at path: "valid"');

        $constraint = new ValidConstraint();
        $constraint->validate('valid', [], $this->getContext());
    }

    public function testWithEmptyArrayValue()
    {
        $constraint = new ValidConstraint();

        self::assertEquals([], $constraint->validate('valid', [], $this->getContext(), $this->getValidator()));
    }

    public function testWithArrayValue()
    {
        $constraint = new ValidConstraint();

        $object = $this->getObject();

        $context = $this->getContext();

        $validator = $this->getValidator([
            'validate' => [
                [
                    'arguments' => [$object, $context, 'valid[0]'],
                    'return' => [],
                ],
            ],
        ]);

        self::assertEquals([], $constraint->validate('valid', [$object], $context, $validator));
    }

    public function testWithArrayCollectionValue()
    {
        $constraint = new ValidConstraint();

        $object = $this->getObject();

        $context = $this->getContext();

        $validator = $this->getValidator([
            'validate' => [
                [
                    'arguments' => [$object, $context, 'valid[0]'],
                    'return' => [],
                ],
            ],
        ]);

        self::assertEquals([], $constraint->validate('valid', new ArrayCollection([$object]), $context, $validator));
    }

    public function testWithObjectValue()
    {
        $constraint = new ValidConstraint();

        $object = $this->getObject();

        $context = $this->getContext();

        $validator = $this->getValidator([
            'validate' => [
                [
                    'arguments' => [$object, $context, 'valid'],
                    'return' => [],
                ],
            ],
        ]);

        self::assertEquals([], $constraint->validate('valid', $object, $context, $validator));
    }

    public function testWithStringValue()
    {
        $constraint = new ValidConstraint();

        $context = $this->getContext();

        $validator = $this->getValidator();

        $error = new Error('valid', 'constraint.valid.invalidtype', ['type' => 'string']);

        self::assertEquals([$error], $constraint->validate('valid', 'test', $context, $validator));
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockForInterface(ValidatorContextInterface::class);

        return $context;
    }

    /**
     * @param array $methods
     *
     * @return ValidatorInterface
     */
    private function getValidator(array $methods = []): ValidatorInterface
    {
        /** @var ValidatorInterface|MockObject $validator */
        $validator = $this->getMockForInterface(ValidatorInterface::class, $methods);

        return $validator;
    }

    /**
     * @return object
     */
    private function getObject()
    {
        return new class() {
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
    }
}
