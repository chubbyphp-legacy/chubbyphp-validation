<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Validator;

use Chubbyphp\Validation\Mapping\ValidationObjectMappingInterface;
use Chubbyphp\Validation\ValidatorLogicException;

interface ValidatorObjectMappingRegistryInterface
{
    /**
     * @param string $class
     *
     * @return ValidationObjectMappingInterface
     *
     * @throws ValidatorLogicException
     */
    public function getObjectMapping(string $class): ValidationObjectMappingInterface;
}
