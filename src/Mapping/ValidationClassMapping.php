<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationClassMapping implements ValidationClassMappingInterface
{
    /**
     * @var array<ConstraintInterface>
     */
    private $constraints;

    /**
     * @var array
     */
    private $groups;

    /**
     * @param array<ConstraintInterface> $constraints
     */
    public function __construct(array $constraints, array $groups)
    {
        $this->constraints = $constraints;
        $this->groups = $groups;
    }

    /**
     * @return array<ConstraintInterface>
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }
}
