<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationPropertyMappingBuilderInterface
{
    /**
     * @param array<ConstraintInterface> $constraints)
     */
    public static function create(string $name, array $constraints): self;

    public function setGroups(array $groups): self;

    public function setAccessor(AccessorInterface $accessor): self;

    public function getMapping(): ValidationPropertyMappingInterface;
}
