<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\CoordinateConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\CoordinateConstraint
 *
 * @internal
 */
final class CoordinateConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new CoordinateConstraint();

        self::assertEquals([], $constraint->validate('coordinate', null, $this->getContext()));
    }

    public function testWithBlankValue(): void
    {
        $constraint = new CoordinateConstraint();

        self::assertEquals([], $constraint->validate('coordinate', '', $this->getContext()));
    }

    public function testInvalidType(): void
    {
        $constraint = new CoordinateConstraint();

        $error = new Error('coordinate', 'constraint.coordinate.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('coordinate', [], $this->getContext()));
    }

    public function testInvalidFormat(): void
    {
        $constraint = new CoordinateConstraint();

        $error = new Error('coordinate', 'constraint.coordinate.invalidformat', ['value' => 'coordinate']);

        self::assertEquals([$error], $constraint->validate('coordinate', 'coordinate', $this->getContext()));
    }

    /**
     * @dataProvider provideCoodinates
     */
    public function testWithCoordinate(string $coordinate): void
    {
        $constraint = new CoordinateConstraint();

        self::assertEquals([], $constraint->validate('coordinate', $coordinate, $this->getContext()));
    }

    public static function provideCoodinates(): iterable
    {
        return [
            ['coordinate' => '-90, -180', 'latitude' => '-90', 'longitude' => '-180'],
            ['coordinate' => '-90.0, -180.0', 'latitude' => '-90.0', 'longitude' => '-180.0'],
            ['coordinate' => '90.0, 180.0', 'latitude' => '90.0', 'longitude' => '180.0'],
            ['coordinate' => '90, 180', 'latitude' => '90', 'longitude' => '180'],
        ];
    }

    /**
     * @dataProvider provideCoodinates
     */
    public function testGetLangitudeAndLongitude(string $coordinate, string $latitude, string $longitude): void
    {
        $matches = [];
        preg_match(CoordinateConstraint::PATTERN, $coordinate, $matches);

        self::assertSame($latitude, $matches[1]);
        self::assertSame($longitude, $matches[3]);
    }

    /**
     * @dataProvider provideWithInvalidCoordinateCases
     */
    public function testWithInvalidCoordinate(string $coordinate): void
    {
        $constraint = new CoordinateConstraint();

        $error = new Error('coordinate', 'constraint.coordinate.invalidvalue', ['value' => $coordinate]);

        self::assertEquals([$error], $constraint->validate('coordinate', $coordinate, $this->getContext()));
    }

    public static function provideWithInvalidCoordinateCases(): iterable
    {
        return [
            ['coordinate' => '-91, -181'],
            ['coordinate' => '-90.1, -180.1'],
            ['coordinate' => '90.1, 180.1'],
            ['coordinate' => '91, 181'],
        ];
    }

    private function getContext(): ValidatorContextInterface
    {
        // @var ValidatorContextInterface|MockObject $context
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
