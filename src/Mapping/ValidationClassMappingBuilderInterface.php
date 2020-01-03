<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationClassMappingBuilderInterface
{
    /**
     * @param array<ConstraintInterface> $constraints
     */
    public static function create(array $constraints): self;

    /**
     * @param array<int, string> $groups
     */
    public function setGroups(array $groups): self;

    public function getMapping(): ValidationClassMappingInterface;
}
