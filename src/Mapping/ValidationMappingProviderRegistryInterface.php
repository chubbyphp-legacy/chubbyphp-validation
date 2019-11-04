<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\ValidatorLogicException;

interface ValidationMappingProviderRegistryInterface
{
    /**
     * @throws ValidatorLogicException
     */
    public function provideMapping(string $class): ValidationMappingProviderInterface;
}
