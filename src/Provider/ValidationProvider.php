<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Provider;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\Validator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class ValidationProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container): void
    {
        $container['validator'] = function () use ($container) {
            return new Validator(
                $container['validator.mappingproviderregistry'],
                $container['logger'] ?? null
            );
        };

        $container['validator.mappingproviderregistry'] = function () use ($container) {
            return new ValidationMappingProviderRegistry($container['validator.mappings']);
        };

        $container['validator.mappings'] = function () {
            return [];
        };
    }
}
