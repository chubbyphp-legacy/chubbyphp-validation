<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationPropertyMappingInterface extends ValidationGroupsInterface
{
    public function getName(): string;

    /**
     * @return array<ConstraintInterface>
     */
    public function getConstraints(): array;

    public function getAccessor(): AccessorInterface;
}
