<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationPropertyMappingBuilderInterface
{
    /**
     * @param string                     $name
     * @param array<ConstraintInterface> $constraints)
     *
     * @return self
     */
    public static function create(string $name, array $constraints): self;

    /**
     * @param array $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @param AccessorInterface $accessor
     *
     * @return self
     */
    public function setAccessor(AccessorInterface $accessor): self;

    /**
     * @return ValidationPropertyMappingInterface
     */
    public function getMapping(): ValidationPropertyMappingInterface;
}
