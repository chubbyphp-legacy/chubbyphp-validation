<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\ChoiceConstraint;
use Chubbyphp\Validation\Error\Error;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\ChoiceConstraint
 */
final class ChoiceConstraintTest extends TestCase
{
    public function testWithInvalidConstructInvalidType()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Type array is invalid, supported types: boolean, double, integer, string');

        new ChoiceConstraint('array', []);
    }

    public function testWithInvalidConstructInvalidChoices()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Choice 0 got type boolean, but type string required');

        new ChoiceConstraint(ChoiceConstraint::TYPE_STRING, [true, false]);
    }

    public function testWithNullValue()
    {
        $constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_STRING, ['active', 'inactive']);

        self::assertEquals([], $constraint->validate('choice', null));
    }

    public function testInvalidType()
    {
        $constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_STRING, ['active', 'inactive']);

        $error = new Error(
            'choice',
            'constraint.choice.invalidtype',
            ['type' => 'array', 'supportedTypes' => 'boolean, double, integer, string']
        );

        self::assertEquals([$error], $constraint->validate('choice', []));
    }

    /**
     * @dataProvider choicesProvider
     *
     * @param string $type
     * @param array  $choices
     * @param mixed  $choice
     */
    public function testWithChoice(string $type, array $choices, $choice)
    {
        $constraint = new ChoiceConstraint($type, $choices);

        self::assertEquals([], $constraint->validate('choice', $choice));
    }

    /**
     * @return array
     */
    public function choicesProvider(): array
    {
        return [
            ['type' => ChoiceConstraint::TYPE_STRING, 'choices' => ['active', 'inactive'], 'choice' => 'active'],
            ['type' => ChoiceConstraint::TYPE_BOOL, 'choices' => [true, false], 'choice' => true],
            ['type' => ChoiceConstraint::TYPE_FLOAT, 'choices' => [1.0, 2.0, 3.0], 'choice' => 1.0],
            ['type' => ChoiceConstraint::TYPE_INT, 'choices' => [1, 2, 3], 'choice' => 1],
        ];
    }

    /**
     * @dataProvider invalidChoicesProvider
     *
     * @param string $type
     * @param array  $choices
     * @param mixed  $choice
     */
    public function testWithInvalidChoice(string $type, array $choices, $choice)
    {
        $constraint = new ChoiceConstraint($type, $choices);

        $error = new Error(
            'choice',
            'constraint.choice.invalidvalue',
            ['input' => $choice, 'choices' => $this->implode($choices)]
        );

        self::assertEquals([$error], $constraint->validate('choice', $choice));
    }

    /**
     * @return array
     */
    public function invalidChoicesProvider(): array
    {
        return [
            ['type' => ChoiceConstraint::TYPE_STRING, 'choices' => ['active', 'inactive'], 'choice' => 'test'],
            ['type' => ChoiceConstraint::TYPE_BOOL, 'choices' => [true], 'choice' => false],
            ['type' => ChoiceConstraint::TYPE_FLOAT, 'choices' => [1.0, 2.0, 3.0], 'choice' => 4.0],
            ['type' => ChoiceConstraint::TYPE_INT, 'choices' => [1, 2, 3], 'choice' => 4],
        ];
    }

    public function testWithStringInBooleanChoice()
    {
        $constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_BOOL, [true, false], true);

        self::assertEquals([], $constraint->validate('choice', '1')); // true
        self::assertEquals([], $constraint->validate('choice', '')); // false
    }

    public function testWithInvalidStringInBooleanChoice()
    {
        $constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_BOOL, [true, false], true);

        $error = new Error(
            'choice',
            'constraint.choice.invalidvalue',
            ['input' => '2', 'choices' => $this->implode(['1', ''])]
        );

        self::assertEquals([$error], $constraint->validate('choice', '2'));
    }

    public function testWithStringInFloatChoice()
    {
        $constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_FLOAT, [1.0, 2.0, 3.0], true);

        self::assertEquals([], $constraint->validate('choice', '1.0'));
        self::assertEquals([], $constraint->validate('choice', '2.0'));
        self::assertEquals([], $constraint->validate('choice', '3.0'));
    }

    public function testWithInvalidStringInFloatChoice()
    {
        $constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_FLOAT, [1.0, 2.0, 3.0], true);

        $error = new Error(
            'choice',
            'constraint.choice.invalidvalue',
            ['input' => '4.0', 'choices' => $this->implode(['1.0', '2.0', '3.0'])]
        );

        self::assertEquals([$error], $constraint->validate('choice', '4.0'));
    }

    public function testWithStringInIntegerChoice()
    {
        $constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_INT, [1, 2, 3], true);

        self::assertEquals([], $constraint->validate('choice', '1'));
        self::assertEquals([], $constraint->validate('choice', '2'));
        self::assertEquals([], $constraint->validate('choice', '3'));
    }

    public function testWithInvalidStringInIntegerChoice()
    {
        $constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_INT, [1, 2, 3], true);

        $error = new Error(
            'choice',
            'constraint.choice.invalidvalue',
            ['input' => '4', 'choices' => $this->implode(['1', '2', '3'])]
        );

        self::assertEquals([$error], $constraint->validate('choice', '4'));
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
}
