<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationClassMappingBuilder implements ValidationClassMappingBuilderInterface
{
    /**
     * @var array<int, ConstraintInterface>
     */
    private $constraints;

    /**
     * @var array<int, string>
     */
    private $groups;

    private function __construct()
    {
    }

    public static function create(array $constraints): ValidationClassMappingBuilderInterface
    {
        $self = new self();
        $self->constraints = [];
        foreach ($constraints as $constraint) {
            $self->addConstraint($constraint);
        }

        return $self;
    }

    public function setGroups(array $groups): ValidationClassMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    public function getMapping(): ValidationClassMappingInterface
    {
        return new ValidationClassMapping(
            $this->constraints,
            $this->groups ?? []
        );
    }

    private function addConstraint(ConstraintInterface $constraint): void
    {
        $this->constraints[] = $constraint;
    }
}
