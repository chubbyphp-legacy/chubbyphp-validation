<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;

interface ValidationPropertyMappingBuilderInterface
{
    public static function create(string $name, array $constraints): self;

    /**
     * @param array<int, string> $groups
     */
    public function setGroups(array $groups): self;

    public function setAccessor(AccessorInterface $accessor): self;

    public function getMapping(): ValidationPropertyMappingInterface;
}
