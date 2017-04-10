<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

final class PropertyMapping implements PropertyMappingInterface
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
     * @param string $name
     * @param array $constraints
     */
    public function __construct(string $name, array $constraints = [])
    {
        $this->name = $name;
        $this->constraints = [];
        foreach ($constraints as $constraint) {
            $this->addConstraint($constraint);
        }
    }

    /**
     * @param ConstraintInterface $constraint
     */
    private function addConstraint(ConstraintInterface $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }
}
