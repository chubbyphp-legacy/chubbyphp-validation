<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class ValidationPropertyMappingBuilder implements ValidationPropertyMappingBuilderInterface
{
    private ?string $name = null;

    /**
     * @var array<int, ConstraintInterface>
     */
    private ?array $constraints = null;

    /**
     * @var array<int, string>
     */
    private ?array $groups = null;

    private ?AccessorInterface $accessor = null;

    private function __construct() {}

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
     * @param array<int, string> $groups
     */
    public function setGroups(array $groups): ValidationPropertyMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    public function setAccessor(AccessorInterface $accessor): ValidationPropertyMappingBuilderInterface
    {
        $this->accessor = $accessor;

        return $this;
    }

    public function getMapping(): ValidationPropertyMappingInterface
    {
        return new ValidationPropertyMapping(
            $this->name,
            $this->constraints,
            $this->groups ?? [],
            $this->accessor ?? new PropertyAccessor($this->name)
        );
    }

    private function addConstraint(ConstraintInterface $constraint): void
    {
        $this->constraints[] = $constraint;
    }
}
