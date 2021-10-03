<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\SortConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\SortConstraint
 *
 * @internal
 */
final class SortConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testValidateWithString(): void
    {
        /** @var MockObject|ValidatorContextInterface $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        $constraint = new SortConstraint(['name']);

        self::assertEquals(
            [new Error('path', 'constraint.sort.invalidtype', ['type' => 'string'])],
            $constraint->validate('path', 'name', $context)
        );
    }

    public function testValidateWithStdClass(): void
    {
        /** @var MockObject|ValidatorContextInterface $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        $constraint = new SortConstraint(['name']);

        self::assertEquals(
            [new Error('path', 'constraint.sort.invalidtype', ['type' => \stdClass::class])],
            $constraint->validate('path', new \stdClass(), $context)
        );
    }

    public function testValidateWithUnsupportedFieldAndUnsupportedOrder(): void
    {
        /** @var MockObject|ValidatorContextInterface $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        $constraint = new SortConstraint(['name']);

        self::assertEquals(
            [
                new Error(
                    'path.unknown',
                    'constraint.sort.field.notallowed',
                    ['field' => 'unknown', 'allowedFields' => ['name']]
                ),
                new Error(
                    'path.unknown',
                    'constraint.sort.order.notallowed',
                    ['field' => 'unknown', 'order' => 'test', 'allowedOrders' => ['asc', 'desc']]
                ),
            ],
            $constraint->validate('path', ['name' => 'asc', 'unknown' => 'test'], $context)
        );
    }

    public function testValidateWithUnsupportedFieldAndUnsupportedOrderType(): void
    {
        /** @var MockObject|ValidatorContextInterface $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        $constraint = new SortConstraint(['name']);

        self::assertEquals(
            [
                new Error(
                    'path.unknown1',
                    'constraint.sort.field.notallowed',
                    ['field' => 'unknown1', 'allowedFields' => ['name']]
                ),
                new Error(
                    'path.unknown1',
                    'constraint.sort.order.invalidtype',
                    ['field' => 'unknown1', 'type' => \stdClass::class]
                ),
                new Error(
                    'path.unknown2',
                    'constraint.sort.field.notallowed',
                    ['field' => 'unknown2', 'allowedFields' => ['name']]
                ),
                new Error(
                    'path.unknown2',
                    'constraint.sort.order.invalidtype',
                    ['field' => 'unknown2', 'type' => 'integer']
                ),
            ],
            $constraint->validate('path', ['name' => 'asc', 'unknown1' => new \stdClass(), 'unknown2' => 1], $context)
        );
    }

    public function testValidate(): void
    {
        /** @var MockObject|ValidatorContextInterface $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        $constraint = new SortConstraint(['name']);

        self::assertSame([], $constraint->validate('path', ['name' => 'asc'], $context));
    }
}
