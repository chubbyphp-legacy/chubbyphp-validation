<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Rules;

use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Validation\ValidatableModelInterface;
use Respect\Validation\Rules\AbstractRule;

class UniqueModelRule extends AbstractRule
{
    /**
     * @var string[]|array
     */
    protected $properties; // needs to be protected (copy within exception)

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @param string[]|array $properties
     */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
        $this->setName(implode(', ', $properties));
    }

    /**
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ValidatableModelInterface $model
     *
     * @return bool
     */
    public function validate($model): bool
    {
        $this->validateInputType($model);
        $this->validateRepositoryIsSet();

        $criteria = $this->getCriteriaFromModel($model);

        /** @var ValidatableModelInterface $modelFromRepository */
        $modelFromRepository = $this->repository->findOneBy($criteria);
        if (null !== $modelFromRepository && $modelFromRepository->getId() !== $model->getId()) {
            return false;
        }

        return true;
    }

    /**
     * @param ValidatableModelInterface $model
     *
     * @throws \InvalidArgumentException
     */
    private function validateInputType($model)
    {
        if (!$model instanceof ValidatableModelInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The to validate value needs to be an instance of %s, %s given!',
                    ValidatableModelInterface::class,
                    get_class($model)
                )
            );
        }
    }

    /**
     * @throws \RuntimeException
     */
    private function validateRepositoryIsSet()
    {
        if (null === $this->repository) {
            throw new \RuntimeException(
                sprintf(
                    'Rule %s needs a repository of interface %s, please call setRepository before validate.',
                    self::class,
                    RepositoryInterface::class
                )
            );
        }
    }

    /**
     * @param ValidatableModelInterface $model
     *
     * @return array
     */
    private function getCriteriaFromModel(ValidatableModelInterface $model): array
    {
        $reflectionClass = new \ReflectionObject($model);

        $criteria = [];
        foreach ($this->properties as $property) {
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $criteria[$property] = $reflectionProperty->getValue($model);
        }

        return $criteria;
    }
}
