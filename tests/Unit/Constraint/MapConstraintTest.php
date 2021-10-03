<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Constraint\MapConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\MapConstraint
 *
 * @internal
 */
final class MapConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new MapConstraint();

        self::assertEquals([], $constraint->validate('map', null, $this->getContext()));
    }

    public function testWithInvalidValue(): void
    {
        $constraint = new MapConstraint();

        self::assertEquals([
            new Error('map', 'constraint.map.invalidtype', ['type' => 'string']),
        ], $constraint->validate('map', 'string', $this->getContext()));
    }

    public function testWithMissingConstraint(): void
    {
        $constraint = new MapConstraint(['name' => [$this->getConstraint()]]);

        self::assertEquals([
            new Error(
                'map.name2',
                'constraint.map.field.notallowed',
                ['field' => 'name2', 'allowedFields' => ['name']]
            ),
        ], $constraint->validate('map', ['name2' => 'example'], $this->getContext(), $this->getValidator()));
    }

    public function testWithConstraint(): void
    {
        $constraint = new MapConstraint(['name' => [$this->getConstraint()]]);

        self::assertEquals(
            [],
            $constraint->validate('map', ['name' => 'example'], $this->getContext(), $this->getValidator())
        );
    }

    public function testWithConstraintAndError(): void
    {
        $constraint = new MapConstraint(['name' => [$this->getConstraint(true)]]);

        self::assertEquals([
            new Error('map.name', 'key', ['value' => 'example']),
        ], $constraint->validate('map', ['name' => 'example'], $this->getContext(), $this->getValidator()));
    }

    public function testWithConstraintButWithoutArray(): void
    {
        error_clear_last();

        $constraint = new MapConstraint(['name' => $this->getConstraint()]);

        $error = error_get_last();

        error_clear_last();

        self::assertNotNull($error);

        self::assertSame(E_USER_DEPRECATED, $error['type']);
        self::assertSame(
            'Constraints by field "name" should be an array of "Chubbyphp\Validation\Constraint\ConstraintInterface"',
            $error['message']
        );

        self::assertEquals(
            [],
            $constraint->validate('map', ['name' => 'example'], $this->getContext(), $this->getValidator())
        );
    }

    private function getValidator(): ValidatorInterface
    {
        // @var ValidatorInterface|MockObject $validator
        return $this->getMockByCalls(ValidatorInterface::class);
    }

    private function getConstraint(bool $error = false): ConstraintInterface
    {
        /** @var ConstraintInterface|MockObject $constraint */
        $constraint = $this
            ->getMockBuilder(ConstraintInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass()
        ;

        $constraint->expects(self::any())->method('validate')->willReturnCallback(
            static function (
                string $path,
                $value,
                ValidatorContextInterface $context,
                ?ValidatorInterface $validator = null
            ) use ($error) {
                if (!$error) {
                    return [];
                }

                return [new Error($path, 'key', ['value' => $value])];
            }
        );

        return $constraint;
    }

    private function getContext(): ValidatorContextInterface
    {
        // @var ValidatorContextInterface|MockObject $context
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
