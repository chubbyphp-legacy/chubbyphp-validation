<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Provider;

use Chubbyphp\Validation\Validator\ValidatorObjectMappingRegistry;
use Chubbyphp\Validation\Validator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class ValidationProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['validator'] = function () use ($container) {
            return new Validator(
                $container['validator.objectmappingregistry'],
                $container['logger'] ?? null
            );
        };

        $container['validator.objectmappingregistry'] = function () use ($container) {
            return new ValidatorObjectMappingRegistry($container['validator.objectmappings']);
        };

        $container['validator.objectmappings'] = function () {
            return [];
        };
    }
}
