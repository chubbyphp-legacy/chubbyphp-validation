<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationPropertyMappingBuilder implements ValidationPropertyMappingBuilderInterface
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
    private $constraints = [];

    /**
     * @var array
     */
    private $groups;

    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param string $name
     * @param array  $constraints
     *
     * @return ValidationPropertyMappingBuilderInterface
     */
    public static function create(string $name): ValidationPropertyMappingBuilderInterface
    {
        $self = new self();
        $self->name = $name;

        return $self;
    }

    /**
     * @param string|null $forceType
     *
     * @return ValidationPropertyMappingBuilderInterface
     */
    public function setForceType(string $forceType = null): ValidationPropertyMappingBuilderInterface
    {
        $this->forceType = $forceType;

        return $this;
    }

    /**
     * @param ConstraintInterface $constraint
     *
     *                                       @return ValidationPropertyMappingBuilderInterface
     */
    public function addConstraint(ConstraintInterface $constraint): ValidationPropertyMappingBuilderInterface
    {
        $this->constraints[] = $constraint;

        return $this;
    }

    /**
     * @param array $groups
     *
     * @return ValidationPropertyMappingBuilderInterface
     */
    public function setGroups(array $groups): ValidationPropertyMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param AccessorInterface $accessor
     *
     * @return ValidationPropertyMappingBuilderInterface
     */
    public function setAccessor(AccessorInterface $accessor): ValidationPropertyMappingBuilderInterface
    {
        $this->accessor = $accessor;

        return $this;
    }

    /**
     * @return ValidationPropertyMappingInterface
     */
    public function getMapping(): ValidationPropertyMappingInterface
    {
        return new ValidationPropertyMapping(
            $this->name,
            $this->forceType,
            $this->constraints,
            $this->groups ?? [],
            $this->accessor ?? new PropertyAccessor($this->name)
        );
    }
}
