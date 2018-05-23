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

    private function __construct()
    {
    }

    /**
     * @param string $name
     * @param array  $constraints
     *
     * @return ValidationPropertyMappingBuilderInterface
     */
    public static function create(string $name, array $constraints): ValidationPropertyMappingBuilderInterface
    {
        $self = new self();
        $self->name = $name;

        $self->constraints = [];
        foreach ($constraints as $constraint) {
            $self->addConstraint($constraint);
        }

        return $self;
    }

    /**
     * @param ConstraintInterface $constraint
     */
    private function addConstraint(ConstraintInterface $constraint)
    {
        $this->constraints[] = $constraint;
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
            $this->constraints,
            $this->groups ?? [],
            $this->accessor ?? new PropertyAccessor($this->name)
        );
    }
}
