<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

interface ValidationObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @param string $path
     *
     * @return ValidationClassMappingInterface|null
     */
    public function getValidationClassMapping(string $path);

    /**
     * @param string $path
     *
     * @return ValidationPropertyMappingInterface[]
     */
    public function getValidationPropertyMappings(string $path): array;
}
