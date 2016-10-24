<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Helper;

use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Validation\Rules\UniqueModelRule;
use Chubbyphp\Validation\ValidatableModelInterface;
use Chubbyphp\Validation\ValidationHelperInterface;
use Respect\Validation\Rules\AbstractRule;

final class RepositoryHelper implements ValidationHelperInterface
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
     * @param UniqueModelRule|AbstractRule $rule
     * @param mixed                        $value
     *
     * @return bool
     */
    public function isResponsible(AbstractRule $rule, $value): bool
    {
        if (!$value instanceof ValidatableModelInterface) {
            return false;
        }

        return get_class($value) === $this->repository->getModelClass();
    }

    /**
     * @param UniqueModelRule|AbstractRule $rule
     * @param mixed                        $value
     */
    public function apply(AbstractRule $rule, $value)
    {
        $rule->setRepository($this->repository);
    }
}
