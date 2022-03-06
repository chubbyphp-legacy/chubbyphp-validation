<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationClassMapping implements ValidationClassMappingInterface
{
    /**
     * @param array<int, ConstraintInterface> $constraints
     * @param array<int, string>              $groups
     */
    public function __construct(private array $constraints, private array $groups)
    {
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
