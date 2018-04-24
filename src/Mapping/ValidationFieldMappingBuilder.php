<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationFieldMappingBuilder implements ValidationFieldMappingBuilderInterface
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

    /**
     * @param string $name
     * @param array  $constraints
     *
     * @return ValidationFieldMappingBuilderInterface
     */
    public static function create(string $name, array $constraints): ValidationFieldMappingBuilderInterface
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
     * @return ValidationFieldMappingBuilderInterface
     */
    public function setGroups(array $groups): ValidationFieldMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param AccessorInterface $accessor
     *
     * @return ValidationFieldMappingBuilderInterface
     */
    public function setAccessor(AccessorInterface $accessor): ValidationFieldMappingBuilderInterface
    {
        $this->accessor = $accessor;

        return $this;
    }

    /**
     * @return ValidationFieldMappingInterface
     */
    public function getMapping(): ValidationFieldMappingInterface
    {
        return new ValidationFieldMapping(
            $this->name,
            $this->constraints,
            $this->groups ?? [],
            $this->accessor ?? new PropertyAccessor($this->name)
        );
    }
}
