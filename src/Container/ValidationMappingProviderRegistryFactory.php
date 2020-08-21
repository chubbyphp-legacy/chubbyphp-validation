<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Container;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Psr\Container\ContainerInterface;

final class ValidationMappingProviderRegistryFactory
{
    public function __invoke(ContainerInterface $container): ValidationMappingProviderRegistryInterface
    {
        return new ValidationMappingProviderRegistry($container->get(ValidationMappingProviderInterface::class.'[]'));
    }
}
