<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Validator\FieldValidatorInterface;

interface ValidationFieldMappingInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array
     */
    public function getGroups(): array;

    /**
     * @return FieldValidatorInterface
     */
    public function getFieldValidator(): FieldValidatorInterface;
}
