<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Requirements;

use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Validation\RequirementInterface;

final class Repository implements RequirementInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function provides(): string
    {
        return 'repository';
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isResponsible($value): bool
    {
        if (!is_object($value)) {
            return false;
        }

        if (method_exists($this->repository, 'isResponsible')) {
            return $this->repository->isResponsible(get_class($value));
        }

        $modelClass = $this->repository->getModelClass();

        return $value instanceof $modelClass;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRequirement()
    {
        return $this->repository;
    }
}
