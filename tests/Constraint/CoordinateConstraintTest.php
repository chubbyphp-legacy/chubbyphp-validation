<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\CoordinateConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\CoordinateConstraint
 */
final class CoordinateConstraintTest extends TestCase
{
    public function testWithNullValue()
    {
        $constraint = new CoordinateConstraint();

        self::assertEquals([], $constraint->validate('coordinate', null, $this->getContext()));
    }

    public function testInvalidType()
    {
        $constraint = new CoordinateConstraint();

        $error = new Error('coordinate', 'constraint.coordinate.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('coordinate', [], $this->getContext()));
    }

    public function testInvalidFormat()
    {
        $constraint = new CoordinateConstraint();

        $error = new Error('coordinate', 'constraint.coordinate.invalidformat', ['value' => 'coordinate']);

        self::assertEquals([$error], $constraint->validate('coordinate', 'coordinate', $this->getContext()));
    }

    /**
     * @dataProvider getCoordinates
     *
     * @param string $coordinate
     */
    public function testWithCoordinate(string $coordinate)
    {
        $constraint = new CoordinateConstraint();

        self::assertEquals([], $constraint->validate('coordinate', $coordinate, $this->getContext()));
    }

    /**
     * @return array
     */
    public function getCoordinates(): array
    {
        return [
            ['coordinate' => '-90, -180', 'latitude' => '-90', 'longitude' => '-180'],
            ['coordinate' => '-90.0, -180.0', 'latitude' => '-90.0', 'longitude' => '-180.0'],
            ['coordinate' => '90.0, 180.0', 'latitude' => '90.0', 'longitude' => '180.0'],
            ['coordinate' => '90, 180', 'latitude' => '90', 'longitude' => '180'],
        ];
    }

    /**
     * @dataProvider getCoordinates
     *
     * @param string $coordinate
     * @param string $latitude
     * @param string $longitude
     */
    public function testGetLangitudeAndLongitude(string $coordinate, string $latitude, string $longitude)
    {
        $matches = [];
        preg_match(CoordinateConstraint::PATTERN, $coordinate, $matches);

        self::assertSame($latitude, $matches[1]);
        self::assertSame($longitude, $matches[3]);
    }

    /**
     * @dataProvider getInvalidCoordinates
     *
     * @param string $coordinate
     */
    public function testWithInvalidCoordinate(string $coordinate)
    {
        $constraint = new CoordinateConstraint();

        $error = new Error('coordinate', 'constraint.coordinate.invalidvalue', ['value' => $coordinate]);

        self::assertEquals([$error], $constraint->validate('coordinate', $coordinate, $this->getContext()));
    }

    /**
     * @return array
     */
    public function getInvalidCoordinates(): array
    {
        return [
            ['coordinate' => '-91, -181'],
            ['coordinate' => '-90.1, -180.1'],
            ['coordinate' => '90.1, 180.1'],
            ['coordinate' => '91, 181'],
        ];
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
