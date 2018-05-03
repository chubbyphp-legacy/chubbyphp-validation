<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\ChoiceConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\ChoiceConstraint
 */
final class ChoiceConstraintTest extends TestCase
{
    public function testWithNullValue()
    {
        $constraint = new ChoiceConstraint(['active', 'inactive']);

        self::assertEquals([], $constraint->validate('choice', null, $this->getContext()));
    }

    /**
     * @dataProvider choicesProvider
     *
     * @param array $choices
     * @param mixed $choice
     */
    public function testWithChoice(array $choices, $choice)
    {
        $constraint = new ChoiceConstraint($choices);

        self::assertEquals([], $constraint->validate('choice', $choice, $this->getContext()));
    }

    /**
     * @return array
     */
    public function choicesProvider(): array
    {
        return [
            ['choices' => ['active', 'inactive'], 'choice' => 'active'],
            ['choices' => [true, false], 'choice' => true],
            ['choices' => [1.0, 2.0, 3.0], 'choice' => 1.0],
            ['choices' => [1, 2, 3], 'choice' => 1],
        ];
    }

    /**
     * @dataProvider invalidChoicesProvider
     *
     * @param array $choices
     * @param mixed $choice
     */
    public function testWithInvalidChoice(array $choices, $choice)
    {
        $constraint = new ChoiceConstraint($choices);

        $error = new Error(
            'choice',
            'constraint.choice.invalidvalue',
            ['value' => $choice, 'choices' => $this->implode($choices)]
        );

        self::assertEquals([$error], $constraint->validate('choice', $choice, $this->getContext()));
    }

    /**
     * @return array
     */
    public function invalidChoicesProvider(): array
    {
        return [
            ['choices' => ['active', 'inactive'], 'choice' => 'test'],
            ['choices' => [true], 'choice' => false],
            ['choices' => [1.0, 2.0, 3.0], 'choice' => 4.0],
            ['choices' => [1, 2, 3], 'choice' => 4],
        ];
    }

    /**
     * @param array $choices
     *
     * @return string
     */
    private function implode(array $choices): string
    {
        return implode(', ', $choices);
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockBuilder(ValidatorContextInterface::class)->getMockForAbstractClass();

        return $context;
    }
}
