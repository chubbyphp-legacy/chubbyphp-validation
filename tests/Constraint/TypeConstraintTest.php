<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\TypeConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\TypeConstraint
 */
final class TypeConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValueAgainstString()
    {
        $constraint = new TypeConstraint('string');

        self::assertEquals([], $constraint->validate('string', null, $this->getContext()));
    }

    public function testWithStringValueAgainstString()
    {
        $constraint = new TypeConstraint('string');

        self::assertEquals([], $constraint->validate('string', 'text', $this->getContext()));
    }

    public function testWithIntegerValueAgainstString()
    {
        $constraint = new TypeConstraint('string');

        $error = new Error('string', 'constraint.type.invalidtype', ['type' => 'integer', 'wishedType' => 'string']);

        self::assertEquals([$error], $constraint->validate('string', 1, $this->getContext()));
    }

    public function testWithStdClassValueAgainstStdClass()
    {
        $constraint = new TypeConstraint(\stdClass::class);

        self::assertEquals([], $constraint->validate('stdClass', new \stdClass(), $this->getContext()));
    }

    public function testWithDateTimeValueAgainstStdClass()
    {
        $constraint = new TypeConstraint(\stdClass::class);

        $error = new Error(
            'stdClass',
            'constraint.type.invalidtype',
            [
                'type' => \DateTime::class,
                'wishedType' => \stdClass::class,
            ]
        );

        self::assertEquals(
            [$error],
            $constraint->validate('stdClass', new \DateTime('2004-02-12T15:19:21+00:00'), $this->getContext())
        );
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        return $context;
    }
}
