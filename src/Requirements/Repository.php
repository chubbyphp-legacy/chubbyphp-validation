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
        return get_class($value) === $this->repository->getModelClass();
    }

    /**
     * @return RepositoryInterface
     */
    public function getRequirement()
    {
        return $this->repository;
    }
}
