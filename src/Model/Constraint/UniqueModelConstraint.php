<?php

declare(strict_types=1);

namespace Chubbyphp\ValidationModel\Constraint;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\ResolverInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class UniqueModelConstraint implements ConstraintInterface
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var string[]
     */
    private $uniqueProperties;

    /**
     * @param ResolverInterface $resolver
     * @param string[]          $uniqueProperties
     */
    public function __construct(ResolverInterface $resolver, array $uniqueProperties)
    {
        $this->resolver = $resolver;
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
        $this->validInputTypeOrException($input);

        $modelClass = get_class($input);

        $criteria = $this->getCriteria($modelClass, $input);

        if (null === $persistedModel = $this->resolver->findOneBy($modelClass, $criteria)) {
            return [];
        }

        if ($persistedModel->getId() === $input->getId()) {
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
     * @param ModelInterface $input
     */
    private function validInputTypeOrException($input)
    {
        if (!$input instanceof ModelInterface) {
            throw new \RuntimeException(
                sprintf('Invalid input, instance of %s needed', ModelInterface::class)
            );
        }
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
}
