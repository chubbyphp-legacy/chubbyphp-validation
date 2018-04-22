<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ValidationDoctrine\Constraint;

use Chubbyphp\Tests\ValidationDoctrine\Resources\Model;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\ValidationDoctrine\Constraint\UniqueModelConstraint;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\ValidationDoctrine\Constraint\UniqueModelConstraint
 */
final class UniqueModelConstraintTest extends TestCase
{
    public function testWithNotExistingModel()
    {
        $managerRegistry = $this->getManagerRegistry(
            $this->getManager(
                $this->getClassMetadata(['idPart1', 'idPart2']),
                $this->getRepository()
            )
        );

        $constraint = new UniqueModelConstraint($managerRegistry, ['name']);
        $constraint->validate('', new Model('idPart1Value1', 'idPart2Value1'));
    }

    public function testWithExistingModelWithSameId()
    {
        $managerRegistry = $this->getManagerRegistry(
            $this->getManager(
                $this->getClassMetadata(['idPart1', 'idPart2']),
                $this->getRepository(new Model('idPart1Value1', 'idPart2Value1'))
            )
        );

        $constraint = new UniqueModelConstraint($managerRegistry, ['name']);
        $constraint->validate('', new Model('idPart1Value1', 'idPart2Value1'));
    }

    public function testWithExistingModelWithDiffrentId()
    {
        $managerRegistry = $this->getManagerRegistry(
            $this->getManager(
                $this->getClassMetadata(['idPart1', 'idPart2']),
                $this->getRepository(new Model('idPart1Value1', 'idPart2Value1'))
            )
        );

        $constraint = new UniqueModelConstraint($managerRegistry, ['name']);

        $error = new Error(
            'name',
            'constraint.uniquemodel.notunique',
            ['uniqueProperties' => 'name']
        );

        self::assertEquals([$error], $constraint->validate('', new Model('idPart1Value1', 'idPart2Value2')));
    }

    /**
     * @param ObjectManager $manager
     *
     * @return ManagerRegistry
     */
    private function getManagerRegistry(ObjectManager $manager): ManagerRegistry
    {
        /** @var ManagerRegistry|\PHPUnit_Framework_MockObject_MockObject $managerRegistry */
        $managerRegistry = $this
            ->getMockBuilder(ManagerRegistry::class)
            ->setMethods(['getManagerForClass'])
            ->getMockForAbstractClass()
        ;

        $managerRegistry->expects(self::any())->method('getManagerForClass')->willReturnCallback(
            function (string $class) use ($manager) {
                return $manager;
            }
        );

        return $managerRegistry;
    }

    /**
     * @param ClassMetadata    $metadata
     * @param ObjectRepository $repository
     *
     * @return ObjectManager
     */
    private function getManager(ClassMetadata $metadata, ObjectRepository $repository): ObjectManager
    {
        /** @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject $manager */
        $manager = $this
            ->getMockBuilder(ObjectManager::class)
            ->setMethods(['getClassMetadata', 'getRepository'])
            ->getMockForAbstractClass()
        ;

        $manager->expects(self::any())->method('getClassMetadata')->willReturnCallback(
            function (string $class) use ($metadata) {
                return $metadata;
            }
        );

        $manager->expects(self::any())->method('getRepository')->willReturnCallback(
            function (string $class) use ($repository) {
                return $repository;
            }
        );

        return $manager;
    }

    /**
     * @param string[] $identifier
     *
     * @return ClassMetadata
     */
    private function getClassMetadata(array $identifier): ClassMetadata
    {
        /** @var ClassMetadata|\PHPUnit_Framework_MockObject_MockObject $metadata */
        $metadata = $this
            ->getMockBuilder(ClassMetadata::class)
            ->setMethods(['getIdentifier'])
            ->getMockForAbstractClass()
        ;

        $metadata->expects(self::any())->method('getIdentifier')->willReturn($identifier);

        return $metadata;
    }

    /**
     * @param object|null $persistedModel
     *
     * @return ObjectRepository
     */
    private function getRepository($persistedModel = null): ObjectRepository
    {
        /** @var ObjectRepository|\PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = $this
            ->getMockBuilder(ObjectRepository::class)
            ->setMethods(['findOneBy'])
            ->getMockForAbstractClass()
        ;

        $repository->expects(self::any())->method('findOneBy')->willReturnCallback(
            function (array $criteria) use ($persistedModel) {
                return $persistedModel;
            }
        );

        return $repository;
    }
}
