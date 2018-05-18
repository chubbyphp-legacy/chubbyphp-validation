<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationPropertyMappingInterface extends ValidationGroupsInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array;

    /**
     * @return AccessorInterface
     */
    public function getAccessor(): AccessorInterface;
}
