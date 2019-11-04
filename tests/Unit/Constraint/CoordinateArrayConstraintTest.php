<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\CoordinateArrayConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\CoordinateArrayConstraint
 *
 * @internal
 */
final class CoordinateArrayConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new CoordinateArrayConstraint();

        self::assertEquals([], $constraint->validate('coordinatearray', null, $this->getContext()));
    }

    public function testInvalidType(): void
    {
        $constraint = new CoordinateArrayConstraint();

        $error = new Error('coordinatearray', 'constraint.coordinatearray.invalidtype', ['type' => 'string']);

        self::assertEquals([$error], $constraint->validate('coordinatearray', '', $this->getContext()));
    }

    public function testInvalidFormat(): void
    {
        $constraint = new CoordinateArrayConstraint();

        $error = new Error('coordinatearray', 'constraint.coordinatearray.invalidformat', ['value' => '[]']);

        self::assertEquals([$error], $constraint->validate('coordinatearray', [], $this->getContext()));
    }

    /**
     * @dataProvider getCoordinateArrays
     */
    public function testWithCoordinateArray(array $coordinate): void
    {
        $constraint = new CoordinateArrayConstraint();

        self::assertEquals([], $constraint->validate('coordinatearray', $coordinate, $this->getContext()));
    }

    public function getCoordinateArrays(): array
    {
        return [
            ['coordinate' => ['lat' => '-90', 'lon' => '-180']],
            ['coordinate' => ['lat' => '-90.0', 'lon' => '-180.0']],
            ['coordinate' => ['lat' => '90.0', 'lon' => '180.0']],
            ['coordinate' => ['lat' => '90', 'lon' => '180']],
        ];
    }

    /**
     * @dataProvider getInvalidCoordinateArrays
     */
    public function testWithInvalidCoordinateArray(array $coordinate): void
    {
        $constraint = new CoordinateArrayConstraint();

        $error = new Error('coordinatearray', 'constraint.coordinatearray.invalidvalue', ['value' => json_encode($coordinate)]);

        self::assertEquals([$error], $constraint->validate('coordinatearray', $coordinate, $this->getContext()));
    }

    public function getInvalidCoordinateArrays(): array
    {
        return [
            ['coordinate' => ['lat' => '-91', 'lon' => '-181']],
            ['coordinate' => ['lat' => '-90.1', 'lon' => '-180.1']],
            ['coordinate' => ['lat' => '90.1', 'lon' => '180.1']],
            ['coordinate' => ['lat' => '91', 'lon' => '181']],
        ];
    }

    private function getContext(): ValidatorContextInterface
    {
        /* @var ValidatorContextInterface|MockObject $context */
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
