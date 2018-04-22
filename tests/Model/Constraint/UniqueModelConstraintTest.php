<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ValidationModel\Constraint;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\ResolverInterface;
use Chubbyphp\Tests\ValidationModel\Resources\Model;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\ValidationModel\Constraint\UniqueModelConstraint;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ValidationModel\Constraint\UniqueModelConstraint
 */
final class UniqueModelConstraintTest extends TestCase
{
    public function testWithInvalidInputExceptException()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Invalid input, instance of Chubbyphp\Model\ModelInterface needed');

        $constraint = new UniqueModelConstraint($this->getResolver(), ['name']);
        $constraint->validate('', null);
    }

    public function testWithNotExistingModel()
    {
        $constraint = new UniqueModelConstraint($this->getResolver(), ['name']);
        $constraint->validate('', Model::create('id1'));
    }

    public function testWithExistingModelWithSameId()
    {
        $constraint = new UniqueModelConstraint($this->getResolver(Model::create('id1')), ['name']);
        $constraint->validate('', Model::create('id1'));
    }

    public function testWithExistingModelWithDiffrentId()
    {
        $constraint = new UniqueModelConstraint($this->getResolver(Model::create('id2')), ['name']);

        $error = new Error(
            'name',
            'constraint.uniquemodel.notunique',
            ['uniqueProperties' => 'name']
        );

        self::assertEquals([$error], $constraint->validate('', Model::create('id1')));
    }

    private function getResolver(ModelInterface $model = null): ResolverInterface
    {
        /** @var ResolverInterface|\PHPUnit_Framework_MockObject_MockObject $resolver */
        $resolver = $this
            ->getMockBuilder(ResolverInterface::class)
            ->setMethods(['findOneBy'])
            ->getMockForAbstractClass()
        ;

        $resolver->expects(self::any())->method('findOneBy')->willReturnCallback(
            function (string $modelClass, array $criteria, array $orderBy = null) use ($model) {
                return $model;
            }
        );

        return $resolver;
    }
}
