<?php

declare(strict_types=1);

namespace Chubbyphp\ValidationDoctrine\Constraint;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

final class UniqueModelConstraint implements ConstraintInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var string[]
     */
    private $uniqueProperties;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param string[]        $uniqueProperties
     */
    public function __construct(ManagerRegistry $managerRegistry, array $uniqueProperties)
    {
        $this->managerRegistry = $managerRegistry;
        $this->uniqueProperties = $uniqueProperties;
    }

    /**
     * @param string                  $path
     * @param mixed                   $input
     * @param ValidatorInterface|null $validator
     *
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input, ValidatorInterface $validator = null): array
    {
        $modelClass = get_class($input);

        $criteria = $this->getCriteria($modelClass, $input);

        $manager = $this->managerRegistry->getManagerForClass($modelClass);
        $repository = $manager->getRepository($modelClass);

        if (null === $persistedModel = $repository->findOneBy($criteria)) {
            return [];
        }

        $metadata = $manager->getClassMetadata($modelClass);

        $identifierValues = $this->getIdentifierValues(
            $modelClass,
            $metadata->getIdentifier(),
            $input,
            $persistedModel
        );

        if ($identifierValues['input'] === $identifierValues['persisted']) {
            return [];
        }

        $errors = [];

        foreach ($this->uniqueProperties as $uniqueProperty) {
            $errors[] = new Error(
                $path !== '' ? $path.'.'.$uniqueProperty : $uniqueProperty,
                'constraint.uniquemodel.notunique',
                ['uniqueProperties' => implode(', ', $this->uniqueProperties)]
            );
        }

        return $errors;
    }

    /**
     * @param string $modelClass
     * @param object $input
     *
     * @return array
     */
    private function getCriteria(string $modelClass, $input): array
    {
        $criteria = [];
        foreach ($this->uniqueProperties as $uniqueProperty) {
            $reflectionProperty = new \ReflectionProperty($modelClass, $uniqueProperty);
            $reflectionProperty->setAccessible(true);

            $criteria[$uniqueProperty] = $reflectionProperty->getValue($input);
        }

        return $criteria;
    }

    /**
     * @param string $modelClass
     * @param array  $identifierParts
     * @param object $input
     * @param object $persistedModel
     *
     * @return array
     */
    private function getIdentifierValues(string $modelClass, array $identifierParts, $input, $persistedModel): array
    {
        $identifierInput = [];
        $identifierPersisted = [];
        foreach ($identifierParts as $identifierPart) {
            $reflectionProperty = new \ReflectionProperty($modelClass, $identifierPart);
            $reflectionProperty->setAccessible(true);

            $identifierInput[$identifierPart] = $reflectionProperty->getValue($input);
            $identifierPersisted[$identifierPart] = $reflectionProperty->getValue($persistedModel);
        }

        return ['input' => $identifierInput, 'persisted' => $identifierPersisted];
    }
}
