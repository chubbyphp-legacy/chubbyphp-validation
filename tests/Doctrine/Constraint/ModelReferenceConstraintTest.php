<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ValidationDoctrine\Constraint;

use Chubbyphp\Tests\ValidationDoctrine\Resources\Model;
use Chubbyphp\Tests\ValidationDoctrine\Resources\ProxyModel;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\ValidationDoctrine\Constraint\ModelReferenceConstraint;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ValidationDoctrine\Constraint\ModelReferenceConstraint
 */
final class ModelReferenceConstraintTest extends TestCase
{
    public function testWithNullableTrueAndRecursiveFalse()
    {
        $constraint = new ModelReferenceConstraint(true, false);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelReference',
                null,
                $this->getValidator()
            )
        );
    }

    public function testWithNullableFalseAndRecursiveTrue()
    {
        $constraint = new ModelReferenceConstraint(false, true);

        $error = new Error(
            'modelReference.name',
            'constraint.some.failed',
            ['key' => 'value']
        );

        self::assertEquals(
            [$error],
            $constraint->validate(
                'modelReference',
                new Model('idPart1', 'idPart2'),
                $this->getValidator([
                    ['path' => 'name', 'key' => 'constraint.some.failed', 'arguments' => ['key' => 'value']],
                ])
            )
        );
    }

    public function testWithNullableFalseAndRecursiveTrueProxy()
    {
        $constraint = new ModelReferenceConstraint(false, true);

        $error = new Error(
            'modelReference.name',
            'constraint.some.failed',
            ['key' => 'value']
        );

        self::assertEquals(
            [$error],
            $constraint->validate(
                'modelReference',
                new ProxyModel('idPart1', 'idPart2'),
                $this->getValidator([
                    ['path' => 'name', 'key' => 'constraint.some.failed', 'arguments' => ['key' => 'value']],
                ])
            )
        );
    }

    public function testWithNullableFalseAndRecursiveFalse()
    {
        $constraint = new ModelReferenceConstraint(false, false);

        $error = new Error(
            'modelReference',
            'constraint.modelreference.null',
            []
        );

        self::assertEquals(
            [$error],
            $constraint->validate(
                'modelReference',
                null,
                $this->getValidator()
            )
        );
    }

    public function testWithNullableTrueAndRecursiveTrue()
    {
        $constraint = new ModelReferenceConstraint(true, true);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelReference',
                null,
                $this->getValidator()
            )
        );
    }

    public function testWithoutValidatorButRecursiveExpectException()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage(
            'Recursive validation is only possible if validator given'
        );

        $constraint = new ModelReferenceConstraint(false, true);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelReference',
                new Model('idPart1', 'idPart2')
            )
        );
    }

    public function testWithoutValidatorAndNotRecursive()
    {
        $constraint = new ModelReferenceConstraint(false, false);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelReference',
                new Model('idPart1', 'idPart2')
            )
        );
    }

    /**
     * @param array $plainErrors
     *
     * @return ValidatorInterface
     */
    private function getValidator(array $plainErrors = null): ValidatorInterface
    {
        /** @var ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject $validator */
        $validator = $this
            ->getMockBuilder(ValidatorInterface::class)
            ->setMethods(['validateObject'])
            ->getMockForAbstractClass()
        ;

        $validator->expects(self::any())->method('validateObject')->willReturnCallback(
            function ($object, string $path) use ($plainErrors) {
                if (null === $plainErrors) {
                    throw new \InvalidArgumentException('Existing model need plain errors');
                }

                $errors = [];
                foreach ($plainErrors as $plainError) {
                    $errors[] = new Error(
                        $path.'.'.$plainError['path'],
                        $plainError['key'],
                        $plainError['arguments']
                    );
                }

                return $errors;
            }
        );

        return $validator;
    }
}
