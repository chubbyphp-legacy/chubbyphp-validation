<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationClassMapping implements ValidationClassMappingInterface
{
    /**
     * @var ConstraintInterface[]
     */
    private $constraints;

    /**
     * @var array
     */
    private $groups;

    /**
     * @param ConstraintInterface[] $constraints
     * @param array                 $groups
     */
    public function __construct(array $constraints, array $groups)
    {
        $this->constraints = $constraints;
        $this->groups = $groups;
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
