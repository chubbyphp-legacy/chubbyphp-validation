<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Validator\FieldValidatorInterface;

interface ValidationFieldMappingBuilderInterface
{
    /**
     * @param string $name
     *
     * @return self
     */
    public static function create(string $name): self;

    /**
     * @param array $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @param FieldValidatorInterface $fieldValidator
     *
     * @return self
     */
    public function setFieldValidator(FieldValidatorInterface $fieldValidator): self;

    /**
     * @return ValidationFieldMappingInterface
     */
    public function getMapping(): ValidationFieldMappingInterface;
}
