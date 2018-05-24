<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationClassMappingBuilder implements ValidationClassMappingBuilderInterface
{
    /**
     * @var ConstraintInterface[]
     */
    private $constraints;

    /**
     * @var array
     */
    private $groups;

    private function __construct()
    {
    }

    /**
     * @param string $name
     * @param array  $constraints
     *
     * @return ValidationClassMappingBuilderInterface
     */
    public static function create(array $constraints): ValidationClassMappingBuilderInterface
    {
        $self = new self();
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
     * @return ValidationClassMappingBuilderInterface
     */
    public function setGroups(array $groups): ValidationClassMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return ValidationClassMappingInterface
     */
    public function getMapping(): ValidationClassMappingInterface
    {
        return new ValidationClassMapping(
            $this->constraints,
            $this->groups ?? []
        );
    }
}
