<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationPropertyMapping implements ValidationPropertyMappingInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $forceType;

    /**
     * @var ConstraintInterface[]
     */
    private $constraints;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param string                $name
     * @param string|null           $forceType
     * @param ConstraintInterface[] $constraints
     * @param array                 $groups
     * @param AccessorInterface     $accessor
     */
    public function __construct(
        string $name,
        string $forceType = null,
        array $constraints,
        array $groups,
        AccessorInterface $accessor)
    {
        $this->name = $name;
        $this->forceType = $forceType;
        $this->constraints = $constraints;
        $this->groups = $groups;
        $this->accessor = $accessor;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getForceType()
    {
        return $this->forceType;
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

    /**
     * @return AccessorInterface
     */
    public function getAccessor(): AccessorInterface
    {
        return $this->accessor;
    }
}
