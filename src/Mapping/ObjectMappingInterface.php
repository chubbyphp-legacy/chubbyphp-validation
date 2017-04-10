<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

interface ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array;
}
