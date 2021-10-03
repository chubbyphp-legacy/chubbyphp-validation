<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

interface ValidationMappingProviderInterface
{
    public function getClass(): string;

    /**
     * @return null|ValidationClassMappingInterface
     */
    public function getValidationClassMapping(string $path);

    /**
     * @return array<int, ValidationPropertyMappingInterface>
     */
    public function getValidationPropertyMappings(string $path): array;
}
