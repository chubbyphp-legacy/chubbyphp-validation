<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationFieldMappingInterface
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
     * @return array
     */
    public function getGroups(): array;

    /**
     * @return AccessorInterface
     */
    public function getAccessor(): AccessorInterface;
}
