<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationPropertyMapping implements ValidationPropertyMappingInterface
{
    /**
     * @param array<int, ConstraintInterface> $constraints
     * @param array<int, string>              $groups
     */
    public function __construct(
        private string $name,
        private array $constraints,
        private array $groups,
        private AccessorInterface $accessor
    ) {}

    public function getName(): string
    {
        return $this->name;
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

    public function getAccessor(): AccessorInterface
    {
        return $this->accessor;
    }
}
