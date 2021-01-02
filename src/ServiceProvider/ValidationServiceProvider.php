<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\ServiceProvider;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\Validator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class ValidationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container['validator'] = static fn () => new Validator(
            $container['validator.mappingproviderregistry'],
            $container['logger'] ?? null
        );

        $container['validator.mappingproviderregistry'] = static fn () => new ValidationMappingProviderRegistry($container['validator.mappings']);

        $container['validator.mappings'] = static fn () => [];
    }
}
