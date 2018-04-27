<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationClassMappingBuilderInterface
{
    /**
     * @param ConstraintInterface[]
     *
     * @return self
     */
    public static function create(array $constraints): self;

    /**
     * @param array $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @return ValidationClassMappingInterface
     */
    public function getMapping(): ValidationClassMappingInterface;
}
