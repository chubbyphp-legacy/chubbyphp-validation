<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface PropertyMappingInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array;
}
