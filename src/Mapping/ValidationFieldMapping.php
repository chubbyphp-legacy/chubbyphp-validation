<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Validator\FieldValidatorInterface;

final class ValidationFieldMapping implements ValidationFieldMappingInterface
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

    /**
     * @param string                     $name
     * @param array                      $groups
     * @param FieldValidatorInterface $fieldValidator
     */
    public function __construct($name, array $groups = [], FieldValidatorInterface $fieldValidator)
    {
        $this->name = $name;
        $this->groups = $groups;
        $this->fieldValidator = $fieldValidator;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return FieldValidatorInterface
     */
    public function getFieldValidator(): FieldValidatorInterface
    {
        return $this->fieldValidator;
    }
}
