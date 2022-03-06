<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint\Doctrine;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\Doctrine\UniqueConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\Doctrine\UniqueConstraint
 *
 * @internal
 */
final class UniqueConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        /** @var MockObject|ObjectManager $objectManager */
        $objectManager = $this->getMockByCalls(ObjectManager::class);

        $constraint = new UniqueConstraint($objectManager, ['name']);

        self::assertEquals([], $constraint->validate('unique', null, $this->getContext()));
    }

    public function testWithNewModel(): void
    {
        $model = new class() {
            private string $id = '81b003cd-3f66-47b9-9526-d6227c122366';
            private string $name = 'name';
        };

        $modelClass = $model::class;

        /** @var MockObject|ObjectRepository $repository */
        $repository = $this->getMockByCalls(ObjectRepository::class, [
            Call::create('findOneBy')->with(['name' => 'name'])->willReturn(null),
        ]);

        /** @var MockObject|ObjectManager $objectManager */
        $objectManager = $this->getMockByCalls(ObjectManager::class, [
            Call::create('getRepository')->with($modelClass)->willReturn($repository),
        ]);

        $constraint = new UniqueConstraint($objectManager, ['name']);

        self::assertEquals([], $constraint->validate('unique', $model, $this->getContext()));
    }

    public function testWithSameExistingModel(): void
    {
        $model = new class() {
            private string $id = '81b003cd-3f66-47b9-9526-d6227c122366';
            private string $name = 'name';
        };

        $modelClass = $model::class;

        /** @var MockObject|ObjectRepository $repository */
        $repository = $this->getMockByCalls(ObjectRepository::class, [
            Call::create('findOneBy')
                ->with(['name' => 'name'])
                ->willReturn($model),
        ]);

        /** @var ClassMetadata|MockObject $classMetadata */
        $classMetadata = $this->getMockByCalls(ClassMetadata::class, [
            Call::create('getIdentifier')->with()->willReturn(['id']),
        ]);

        /** @var MockObject|ObjectManager $objectManager */
        $objectManager = $this->getMockByCalls(ObjectManager::class, [
            Call::create('getRepository')->with($modelClass)->willReturn($repository),
            Call::create('getClassMetadata')->with($modelClass)->willReturn($classMetadata),
        ]);

        $constraint = new UniqueConstraint($objectManager, ['name']);

        self::assertEquals([], $constraint->validate('unique', $model, $this->getContext()));
    }

    public function testWithSameDifferentModel(): void
    {
        $model = new class() {
            private string $id = '81b003cd-3f66-47b9-9526-d6227c122366';
            private string $name = 'name';
        };

        $anotherModel = new class() {
            private string $id = 'ab31de6d-e005-469a-9a4e-8066a7ceb178';
            private string $name = 'name';
        };

        $modelClass = $model::class;

        /** @var MockObject|ObjectRepository $repository */
        $repository = $this->getMockByCalls(ObjectRepository::class, [
            Call::create('findOneBy')
                ->with(['name' => 'name'])
                ->willReturn($anotherModel),
        ]);

        /** @var ClassMetadata|MockObject $classMetadata */
        $classMetadata = $this->getMockByCalls(ClassMetadata::class, [
            Call::create('getIdentifier')->with()->willReturn(['id']),
        ]);

        /** @var MockObject|ObjectManager $objectManager */
        $objectManager = $this->getMockByCalls(ObjectManager::class, [
            Call::create('getRepository')->with($modelClass)->willReturn($repository),
            Call::create('getClassMetadata')->with($modelClass)->willReturn($classMetadata),
        ]);

        $constraint = new UniqueConstraint($objectManager, ['name']);

        self::assertEquals([
            new Error(
                'unique.name',
                'constraint.unique.notunique',
                ['uniqueProperties' => 'name']
            ),
        ], $constraint->validate('unique', $model, $this->getContext()));
    }

    private function getContext(): ValidatorContextInterface
    {
        // @var ValidatorContextInterface|MockObject $context
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
