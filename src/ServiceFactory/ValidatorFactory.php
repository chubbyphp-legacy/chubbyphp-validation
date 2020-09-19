<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\ServiceFactory;

use Chubbyphp\Laminas\Config\Factory\AbstractFactory;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Chubbyphp\Validation\Validator;
use Chubbyphp\Validation\ValidatorInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class ValidatorFactory extends AbstractFactory
{
    public function __invoke(ContainerInterface $container): ValidatorInterface
    {
        /** @var ValidationMappingProviderRegistryInterface $validationMappingProviderRegistry */
        $validationMappingProviderRegistry = $this->resolveDependency(
            $container,
            ValidationMappingProviderRegistryInterface::class,
            ValidationMappingProviderRegistryFactory::class
        );

        /** @var LoggerInterface $logger */
        $logger = $container->has(LoggerInterface::class) ? $container->get(LoggerInterface::class) : new NullLogger();

        return new Validator($validationMappingProviderRegistry, $logger);
    }
}
