<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationClassMapping implements ValidationClassMappingInterface
{
    /**
     * @var array<int, ConstraintInterface>
     */
    private $constraints;

    /**
     * @var array<int, string>
     */
    private $groups;

    /**
     * @param array<int, ConstraintInterface> $constraints
     * @param array<int, string>              $groups
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

    /**
     * @return array<int, string>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
