<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Psr\Container\ContainerInterface;

final class LazyValidationMappingProvider implements ValidationMappingProviderInterface
{
    /**
     * @param string $serviceId
     */
    public function __construct(private ContainerInterface $container, private $serviceId, private string $class) {}

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return null|ValidationClassMappingInterface
     */
    public function getValidationClassMapping(string $path)
    {
        return $this->container->get($this->serviceId)->getValidationClassMapping($path);
    }

    /**
     * @return array<int, ValidationPropertyMappingInterface>
     */
    public function getValidationPropertyMappings(string $path): array
    {
        return $this->container->get($this->serviceId)->getValidationPropertyMappings($path);
    }
}
