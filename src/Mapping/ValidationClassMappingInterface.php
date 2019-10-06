<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationClassMappingInterface extends ValidationGroupsInterface
{
    /**
     * @return array<ConstraintInterface>
     */
    public function getConstraints(): array;
}
