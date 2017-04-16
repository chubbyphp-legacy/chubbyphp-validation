<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array;

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array;
}
