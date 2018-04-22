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
     * @param string      $path
     * @param string|null $type
     *
     * @return ValidationFieldMappingInterface[]
     */
    public function getValidationFieldMappings(string $path, string $type = null): array;
}
