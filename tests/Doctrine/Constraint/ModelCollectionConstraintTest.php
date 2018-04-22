<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ValidationDoctrine\Constraint;

use Chubbyphp\Tests\ValidationDoctrine\Resources\Model;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\ValidationDoctrine\Constraint\ModelCollectionConstraint;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ValidationDoctrine\Constraint\ModelCollectionConstraint
 */
final class ModelCollectionConstraintTest extends TestCase
{
    public function testWithInvalidInputExceptException()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage(
            'Invalid input, instance of Doctrine\Common\Collections\Collection needed'
        );

        $constraint = new ModelCollectionConstraint();
        $constraint->validate('modelCollection', null);
    }

    public function testWithoutMinAndMax()
    {
        $constraint = new ModelCollectionConstraint(true);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2')]),
                $this->getValidator([[]])
            )
        );
    }

    public function testWithMin()
    {
        $constraint = new ModelCollectionConstraint(true, 1);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2')]),
                $this->getValidator([[]])
            )
        );
    }

    public function testWithMinButToLessValues()
    {
        $constraint = new ModelCollectionConstraint(true, 2);

        $error = new Error(
            'modelCollection[_all]',
            'constraint.modelcollection.outofrange',
            ['count' => 1, 'min' => 2, 'max' => null]
        );

        self::assertEquals(
            [$error],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2')]),
                $this->getValidator([[]])
            )
        );
    }

    public function testWithMax()
    {
        $constraint = new ModelCollectionConstraint(true, null, 1);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2')]),
                $this->getValidator([[]])
            )
        );
    }

    public function testWithMaxButToManyValues()
    {
        $constraint = new ModelCollectionConstraint(true, null, 0);

        $error = new Error(
            'modelCollection[_all]',
            'constraint.modelcollection.outofrange',
            ['count' => 1, 'min' => null, 'max' => 0]
        );

        self::assertEquals(
            [$error],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2')]),
                $this->getValidator([[]])
            )
        );
    }

    public function testWithMinAndMax()
    {
        $constraint = new ModelCollectionConstraint(true, 1, 2);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2')]),
                $this->getValidator([[]])
            )
        );
    }

    public function testWithMinAndMaxToLessValues()
    {
        $constraint = new ModelCollectionConstraint(true, 1, 2);

        $error = new Error(
            'modelCollection[_all]',
            'constraint.modelcollection.outofrange',
            ['count' => 0, 'min' => 1, 'max' => 2]
        );

        self::assertEquals(
            [$error],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([]),
                $this->getValidator([[]])
            )
        );
    }

    public function testWithMinAndMaxToManyValues()
    {
        $constraint = new ModelCollectionConstraint(true, 1, 2);

        $error = new Error(
            'modelCollection[_all]',
            'constraint.modelcollection.outofrange',
            ['count' => 3, 'min' => 1, 'max' => 2]
        );

        self::assertEquals(
            [$error],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2'), new Model('idPart1', 'idPart2'), new Model('idPart1', 'idPart2')]),
                $this->getValidator([[], [], []])
            )
        );
    }

    public function testWithModelErrors()
    {
        $constraint = new ModelCollectionConstraint(true);

        $errors = [
            new Error(
                'modelCollection[0].name',
                'constraint.some.failed',
                ['key' => 'value']
            ),
            new Error(
                'modelCollection[2].name',
                'constraint.another.failed',
                []
            ),
        ];

        self::assertEquals(
            $errors,
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2'), new Model('idPart1', 'idPart2'), new Model('idPart1', 'idPart2')]),
                $this->getValidator([
                    [['path' => 'name', 'key' => 'constraint.some.failed', 'arguments' => ['key' => 'value']]],
                    [],
                    [['path' => 'name', 'key' => 'constraint.another.failed', 'arguments' => []]],
                ])
            )
        );
    }

    public function testWithMinAndMaxToManyValuesWithModelErrors()
    {
        $constraint = new ModelCollectionConstraint(true, 1, 2);

        $errors = [
            new Error(
                'modelCollection[_all]',
                'constraint.modelcollection.outofrange',
                ['count' => 3, 'min' => 1, 'max' => 2]
            ),
            new Error(
                'modelCollection[0].name',
                'constraint.some.failed',
                ['key' => 'value']
            ),
            new Error(
                'modelCollection[2].name',
                'constraint.another.failed',
                []
            ),
        ];

        self::assertEquals(
            $errors,
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2'), new Model('idPart1', 'idPart2'), new Model('idPart1', 'idPart2')]),
                $this->getValidator([
                    [['path' => 'name', 'key' => 'constraint.some.failed', 'arguments' => ['key' => 'value']]],
                    [],
                    [['path' => 'name', 'key' => 'constraint.another.failed', 'arguments' => []]],
                ])
            )
        );
    }

    public function testWithoutValidatorButRecursiveExpectException()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage(
            'Recursive validation is only possible if validator given'
        );

        $constraint = new ModelCollectionConstraint(true);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2')])
            )
        );
    }

    public function testWithoutValidatorAndNotRecursive()
    {
        $constraint = new ModelCollectionConstraint(false);

        self::assertEquals(
            [],
            $constraint->validate(
                'modelCollection',
                $this->getModelCollection([new Model('idPart1', 'idPart2')])
            )
        );
    }

    /**
     * @param ModelInterface[]
     *
     * @return Collection
     */
    private function getModelCollection(array $models): Collection
    {
        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $modelCollection */
        $modelCollection = $this
            ->getMockBuilder(Collection::class)
            ->setMethods(['count', 'toArray'])
            ->getMockForAbstractClass()
        ;

        $modelCollection->expects(self::any())->method('count')->willReturn(count($models));
        $modelCollection->expects(self::any())->method('toArray')->willReturn($models);

        return $modelCollection;
    }

    /**
     * @param array $plainModelErrors
     *
     * @return ValidatorInterface
     */
    private function getValidator(array $plainModelErrors): ValidatorInterface
    {
        /** @var ValidatorInterface|\PHPUnit_Framework_MockObject_MockObject $validator */
        $validator = $this
            ->getMockBuilder(ValidatorInterface::class)
            ->setMethods(['validateObject'])
            ->getMockForAbstractClass()
        ;

        $validatedModelCounter = 0;

        $validator->expects(self::any())->method('validateObject')->willReturnCallback(
            function ($object, string $path) use (&$plainModelErrors, &$validatedModelCounter) {
                ++$validatedModelCounter;
                if (null === $plainErrors = array_shift($plainModelErrors)) {
                    throw new \InvalidArgumentException(
                        sprintf('Missing errors for the %d object', $validatedModelCounter)
                    );
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
