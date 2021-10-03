<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationPropertyMapping implements ValidationPropertyMappingInterface
{
    private string $name;

    /**
     * @var array<int, ConstraintInterface>
     */
    private array $constraints;

    /**
     * @var array<int, string>
     */
    private array $groups;

    private AccessorInterface $accessor;

    /**
     * @param array<int, ConstraintInterface> $constraints
     * @param array<int, string>              $groups
     */
    public function __construct(
        string $name,
        array $constraints,
        array $groups,
        AccessorInterface $accessor
    ) {
        $this->name = $name;
        $this->constraints = $constraints;
        $this->groups = $groups;
        $this->accessor = $accessor;
    }

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
