<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\ValidConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\ValidConstraint
 *
 * @internal
 */
final class ValidConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new ValidConstraint();

        self::assertEquals([], $constraint->validate('valid', null, $this->getContext()));
    }

    public function testWithMissingValidator(): void
    {
        self::expectException(ValidatorLogicException::class);
        self::expectExceptionMessage('There is no validator at path: "valid"');

        $constraint = new ValidConstraint();
        $constraint->validate('valid', [], $this->getContext());
    }

    public function testWithEmptyArrayValue(): void
    {
        $constraint = new ValidConstraint();

        self::assertEquals([], $constraint->validate('valid', [], $this->getContext(), $this->getValidator()));
    }

    public function testWithArrayValue(): void
    {
        $constraint = new ValidConstraint();

        $object = $this->getObject();

        $context = $this->getContext();

        $validator = $this->getValidator([
            Call::create('validate')->with($object, $context, 'valid[0]')->willReturn([]),
        ]);

        self::assertEquals([], $constraint->validate('valid', [$object], $context, $validator));
    }

    public function testWithArrayCollectionValue(): void
    {
        $constraint = new ValidConstraint();

        $object = $this->getObject();

        $context = $this->getContext();

        $validator = $this->getValidator([
            Call::create('validate')->with($object, $context, 'valid[0]')->willReturn([]),
        ]);

        self::assertEquals([], $constraint->validate('valid', new ArrayCollection([$object]), $context, $validator));
    }

    public function testWithObjectValue(): void
    {
        $constraint = new ValidConstraint();

        $object = $this->getObject();

        $context = $this->getContext();

        $validator = $this->getValidator([
            Call::create('validate')->with($object, $context, 'valid')->willReturn([]),
        ]);

        self::assertEquals([], $constraint->validate('valid', $object, $context, $validator));
    }

    public function testWithStringValue(): void
    {
        $constraint = new ValidConstraint();

        $context = $this->getContext();

        $validator = $this->getValidator();

        $error = new Error('valid', 'constraint.valid.invalidtype', ['type' => 'string']);

        self::assertEquals([$error], $constraint->validate('valid', 'test', $context, $validator));
    }

    private function getContext(): ValidatorContextInterface
    {
        /* @var ValidatorContextInterface|MockObject $context */
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }

    private function getValidator(array $methods = []): ValidatorInterface
    {
        /* @var ValidatorInterface|MockObject $validator */
        return $this->getMockByCalls(ValidatorInterface::class, $methods);
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

            public function getName(): string
            {
                return $this->name;
            }

            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }
        };
    }
}
