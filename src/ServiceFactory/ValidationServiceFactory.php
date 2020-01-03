<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\ServiceFactory;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\Validator;
use Psr\Container\ContainerInterface;

final class ValidationServiceFactory
{
    /**
     * @return array<string, callable>
     */
    public function __invoke(): array
    {
        return [
            'validator' => static function (ContainerInterface $container) {
                return new Validator(
                    $container->get('validator.mappingproviderregistry'),
                    $container->has('logger') ? $container->get('logger') : null
                );
            },
            'validator.mappingproviderregistry' => static function (ContainerInterface $container) {
                return new ValidationMappingProviderRegistry($container->get('validator.mappings'));
            },
            'validator.mappings' => static function () {
                return [];
            },
        ];
    }
}
