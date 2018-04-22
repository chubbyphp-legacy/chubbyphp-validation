<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\Validator\FieldValidator;
use Chubbyphp\Validation\Validator\FieldValidatorInterface;

final class ValidationFieldMappingBuilder implements ValidationFieldMappingBuilderInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $groups;

    /**
     * @var FieldValidatorInterface
     */
    private $fieldValidator;

    private function __construct()
    {
    }

    /**
     * @param string $name
     *
     * @return ValidationFieldMappingBuilderInterface
     */
    public static function create(string $name): ValidationFieldMappingBuilderInterface
    {
        $self = new self();
        $self->name = $name;
        $self->groups = [];
        $self->fieldValidator = new FieldValidator(new PropertyAccessor($name));

        return $self;
    }

    /**
     * @param array $groups
     *
     * @return ValidationFieldMappingBuilderInterface
     */
    public function setGroups(array $groups): ValidationFieldMappingBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param FieldValidatorInterface $fieldValidator
     *
     * @return ValidationFieldMappingBuilderInterface
     */
    public function setFieldValidator(
        FieldValidatorInterface $fieldValidator
    ): ValidationFieldMappingBuilderInterface {
        $this->fieldValidator = $fieldValidator;

        return $this;
    }

    /**
     * @return ValidationFieldMappingInterface
     */
    public function getMapping(): ValidationFieldMappingInterface
    {
        return new ValidationFieldMapping($this->name, $this->groups, $this->fieldValidator);
    }
}
