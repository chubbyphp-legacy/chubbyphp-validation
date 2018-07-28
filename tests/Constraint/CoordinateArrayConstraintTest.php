<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\CoordinateArrayConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\CoordinateArrayConstraint
 */
final class CoordinateArrayConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue()
    {
        $constraint = new CoordinateArrayConstraint();

        self::assertEquals([], $constraint->validate('coordinatearray', null, $this->getContext()));
    }

    public function testInvalidType()
    {
        $constraint = new CoordinateArrayConstraint();

        $error = new Error('coordinatearray', 'constraint.coordinatearray.invalidtype', ['type' => 'string']);

        self::assertEquals([$error], $constraint->validate('coordinatearray', '', $this->getContext()));
    }

    public function testInvalidFormat()
    {
        $constraint = new CoordinateArrayConstraint();

        $error = new Error('coordinatearray', 'constraint.coordinatearray.invalidformat', ['value' => '[]']);

        self::assertEquals([$error], $constraint->validate('coordinatearray', [], $this->getContext()));
    }

    /**
     * @dataProvider getCoordinateArrays
     *
     * @param array $coordinate
     */
    public function testWithCoordinateArray(array $coordinate)
    {
        $constraint = new CoordinateArrayConstraint();

        self::assertEquals([], $constraint->validate('coordinatearray', $coordinate, $this->getContext()));
    }

    /**
     * @return array
     */
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
     *
     * @param array $coordinate
     */
    public function testWithInvalidCoordinateArray(array $coordinate)
    {
        $constraint = new CoordinateArrayConstraint();

        $error = new Error('coordinatearray', 'constraint.coordinatearray.invalidvalue', ['value' => json_encode($coordinate)]);

        self::assertEquals([$error], $constraint->validate('coordinatearray', $coordinate, $this->getContext()));
    }

    /**
     * @return array
     */
    public function getInvalidCoordinateArrays(): array
    {
        return [
            ['coordinate' => ['lat' => '-91', 'lon' => '-181']],
            ['coordinate' => ['lat' => '-90.1', 'lon' => '-180.1']],
            ['coordinate' => ['lat' => '90.1', 'lon' => '180.1']],
            ['coordinate' => ['lat' => '91', 'lon' => '181']],
        ];
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
