<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\ServiceFactory;

use Chubbyphp\Laminas\Config\Factory\AbstractFactory;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Psr\Container\ContainerInterface;

final class ValidationMappingProviderRegistryFactory extends AbstractFactory
{
    public function __invoke(ContainerInterface $container): ValidationMappingProviderRegistryInterface
    {
        return new ValidationMappingProviderRegistry(
            $container->get(ValidationMappingProviderInterface::class.'[]'.$this->name)
        );
    }
}
