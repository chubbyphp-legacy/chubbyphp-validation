<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Doctrine;

use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class UniqueConstraint implements ConstraintInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var string[]
     */
    private $uniqueProperties;

    /**
     * @param string[] $uniqueProperties
     */
    public function __construct(ObjectManager $objectManager, array $uniqueProperties)
    {
        $this->objectManager = $objectManager;
        $this->uniqueProperties = $uniqueProperties;
    }

    /**
     * @param mixed $model
     *
     * @return array<ErrorInterface>
     */
    public function validate(
        string $path,
        $model,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        if (null === $model) {
            return [];
        }

        $criteria = $this->getCriteria($model);

        $modelClass = get_class($model);

        if (null === $persistedModel = $this->objectManager->getRepository($modelClass)->findOneBy($criteria)) {
            return [];
        }

        $metadata = $this->objectManager->getClassMetadata($modelClass);

        if ($this->isValueSameAsPersistedValue($metadata->getIdentifier(), $model, $persistedModel)) {
            return [];
        }

        $errors = [];

        foreach ($this->uniqueProperties as $uniqueProperty) {
            $errors[] = new Error(
                '' !== $path ? $path.'.'.$uniqueProperty : $uniqueProperty,
                'constraint.unique.notunique',
                ['uniqueProperties' => implode(', ', $this->uniqueProperties)]
            );
        }

        return $errors;
    }

    /**
     * @param object $model
     */
    private function getCriteria($model): array
    {
        $criteria = [];
        foreach ($this->uniqueProperties as $uniqueProperty) {
            $criteria[$uniqueProperty] = (new PropertyAccessor($uniqueProperty))->getValue($model);
        }

        return $criteria;
    }

    /**
     * @param object $model
     * @param object $persistedModel
     */
    private function isValueSameAsPersistedValue(array $identifierParts, $model, $persistedModel): bool
    {
        foreach ($identifierParts as $identifierPart) {
            $accessor = new PropertyAccessor($identifierPart);

            if ($accessor->getValue($model) !== $accessor->getValue($persistedModel)) {
                return false;
            }
        }

        return true;
    }
}
