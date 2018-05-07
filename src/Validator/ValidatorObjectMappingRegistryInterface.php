<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Validator;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\ValidatorLogicException;

interface ValidatorObjectMappingRegistryInterface
{
    /**
     * @param string $class
     *
     * @return ValidationMappingProviderInterface
     *
     * @throws ValidatorLogicException
     */
    public function getObjectMapping(string $class): ValidationMappingProviderInterface;
}
