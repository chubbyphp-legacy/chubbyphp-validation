<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\ValidatorLogicException;

interface ValidationMappingProviderRegistryInterface
{
    /**
     * @param string $class
     *
     * @throws ValidatorLogicException
     *
     * @return ValidationMappingProviderInterface
     */
    public function provideMapping(string $class): ValidationMappingProviderInterface;
}
