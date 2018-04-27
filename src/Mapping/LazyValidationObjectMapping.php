<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Psr\Container\ContainerInterface;

final class LazyValidationObjectMapping implements ValidationObjectMappingInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $serviceId;

    /**
     * @var string
     */
    private $class;

    /**
     * @param ContainerInterface $container
     * @param string             $serviceId
     * @param string             $class
     */
    public function __construct(ContainerInterface $container, $serviceId, string $class)
    {
        $this->container = $container;
        $this->serviceId = $serviceId;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $path
     *
     * @return ValidationPropertyMappingInterface[]
     */
    public function getValidationPropertyMappings(string $path): array
    {
        return $this->container->get($this->serviceId)->getValidationFieldMappings($path);
    }
}
