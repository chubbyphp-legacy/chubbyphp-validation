<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Constraint\TraversableConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

/**
 * @covers \Chubbyphp\Validation\Constraint\TraversableConstraint
 */
final class TraversableConstraintTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNullValue()
    {
        $constraint = new TraversableConstraint();

        self::assertEquals([], $constraint->validate('traversable', null));
    }

    public function testWithInvalidValue()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage(
            'Invalid input, array or Traversable needed'
        );

        $constraint = new TraversableConstraint();

        self::assertEquals([], $constraint->validate('traversable', 'string'));
    }

    public function testWithWithoutValidator()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage(
            'Recursive validation is only possible if validator given'
        );

        $constraint = new TraversableConstraint();

        self::assertEquals([], $constraint->validate('traversable', ['string']));
    }

    public function testWithToFewElements()
    {
        $constraint = new TraversableConstraint([], 1);

        self::assertEquals([
            new Error(
                'traversable[_all]',
                'constraint.traversable.outofrange',
                ['count' => 0, 'min' => 1, 'max' => null]
            ),
        ], $constraint->validate('traversable', [], $this->getValidator()));
    }

    public function testWithToManyElements()
    {
        $constraint = new TraversableConstraint([], null, 1);

        self::assertEquals([
            new Error(
                'traversable[_all]',
                'constraint.traversable.outofrange',
                ['count' => 2, 'min' => null, 'max' => 1]
            ),
        ], $constraint->validate('traversable', ['string', 'string'], $this->getValidator()));
    }

    public function testWithoutConstraint()
    {
        $constraint = new TraversableConstraint([]);

        self::assertEquals([], $constraint->validate('traversable', ['string'], $this->getValidator()));
    }

    public function testWithConstraint()
    {
        $constraint = new TraversableConstraint([$this->getConstraint()]);

        self::assertEquals([], $constraint->validate('traversable', ['string'], $this->getValidator()));
    }

    public function testWithConstraintAndError()
    {
        $constraint = new TraversableConstraint([$this->getConstraint(true)]);

        self::assertEquals([
            $this->getError('traversable[0]', 'constraint.name.invalidType', []),
        ], $constraint->validate('traversable', ['string'], $this->getValidator()));
    }

    /**
     * @return ValidatorInterface
     */
    private function getValidator(): ValidatorInterface
    {
        /** @var ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject $validator */
        $validator = $this
            ->getMockBuilder(ValidatorInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass()
        ;

        return $validator;
    }

    /**
     * @param bool $error
     *
     * @return ConstraintInterface
     */
    private function getConstraint(bool $error = false): ConstraintInterface
    {
        /** @var ConstraintInterface|\PHPUnit_Framework_MockObject_MockObject $constraint */
        $constraint = $this
            ->getMockBuilder(ConstraintInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass()
        ;

        $constraint->expects(self::any())->method('validate')->willReturnCallback(
            function (string $path, $input, ValidatorInterface $validator = null) use ($error) {
                if (!$error) {
                    return [];
                }

                return [$this->getError($path, $input, [])];
            }
        );

        return $constraint;
    }

    /**
     * @param string $path
     * @param string $key
     * @param array  $arguments
     *
     * @return ErrorInterface
     */
    private function getError(string $path, string $key, array $arguments): ErrorInterface
    {
        /** @var ErrorInterface|\PHPUnit_Framework_MockObject_MockObject $error */
        $error = $this
            ->getMockBuilder(ErrorInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass()
        ;

        $error->expects(self::any())->method('getPath')->willReturnCallback(
            function () use ($path) {
                return $path;
            }
        );

        $error->expects(self::any())->method('getKey')->willReturnCallback(
            function () use ($key) {
                return $key;
            }
        );

        $error->expects(self::any())->method('getArguments')->willReturnCallback(
            function () use ($arguments) {
                return $arguments;
            }
        );

        return $error;
    }
}
