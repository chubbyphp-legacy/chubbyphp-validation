<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Container;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Chubbyphp\Validation\Validator;
use Chubbyphp\Validation\ValidatorInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @deprecated \Chubbyphp\Validation\ServiceFactory\ValidatorFactory
 */
final class ValidatorFactory
{
    public function __invoke(ContainerInterface $container): ValidatorInterface
    {
        return new Validator(
            $container->get(ValidationMappingProviderRegistryInterface::class),
            $container->get(LoggerInterface::class)
        );
    }
}
