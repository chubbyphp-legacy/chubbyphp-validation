<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ValidationModel\Constraint;

use Chubbyphp\Model\Reference\ModelReferenceInterface;
use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\ValidationModel\Constraint\ModelReferenceConstraint;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ValidationModel\Constraint\ModelReferenceConstraint
 */
final class ModelReferenceConstraintTest extends TestCase
{
    public function testWithInvalidInputExceptException()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage(
            'Invalid input, instance of Chubbyphp\Model\Reference\ModelReferenceInterface needed'
        );

        $constraint = new ModelReferenceConstraint();
        $constraint->validate('modelReference', null);
    }

    public function testWillNullableTrueAndRecursiveFalse()
    {
        $constraint = new ModelReferenceConstraint(true, false);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelReference',
                $this->getModelReference(),
                $this->getValidator()
            )
        );
    }

    public function testWillNullableFalseAndRecursiveTrue()
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
                $this->getModelReference($this->getModel()),
                $this->getValidator([
                    ['path' => 'name', 'key' => 'constraint.some.failed', 'arguments' => ['key' => 'value']],
                ])
            )
        );
    }

    public function testWillNullableFalseAndRecursiveFalse()
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
                $this->getModelReference(),
                $this->getValidator()
            )
        );
    }

    public function testWillNullableTrueAndRecursiveTrue()
    {
        $constraint = new ModelReferenceConstraint(true, true);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelReference',
                $this->getModelReference(),
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
                $this->getModelReference($this->getModel())
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
                $this->getModelReference($this->getModel())
            )
        );
    }

    /**
     * @param ModelInterface|null $model
     *
     * @return ModelReferenceInterface
     */
    private function getModelReference(ModelInterface $model = null): ModelReferenceInterface
    {
        /** @var ModelReferenceInterface|\PHPUnit_Framework_MockObject_MockObject $modelReference */
        $modelReference = $this
            ->getMockBuilder(ModelReferenceInterface::class)
            ->setMethods(['getModel'])
            ->getMockForAbstractClass()
        ;

        $modelReference->expects(self::any())->method('getModel')->willReturn($model);

        return $modelReference;
    }

    /**
     * @return ModelInterface
     */
    private function getModel(): ModelInterface
    {
        /** @var ModelInterface|\PHPUnit_Framework_MockObject_MockObject $model */
        $model = $this
            ->getMockBuilder(ModelInterface::class)
            ->getMockForAbstractClass()
        ;

        return $model;
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
