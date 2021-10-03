<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

final class CallableValidationMappingProvider implements ValidationMappingProviderInterface
{
    private string $class;

    /**
     * @var callable
     */
    private $callable;

    private ?ValidationMappingProviderInterface $mapping = null;

    public function __construct(string $class, callable $callable)
    {
        $this->class = $class;
        $this->callable = $callable;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return null|ValidationClassMappingInterface
     */
    public function getValidationClassMapping(string $path)
    {
        return $this->getMapping()->getValidationClassMapping($path);
    }

    /**
     * @return array<int, ValidationPropertyMappingInterface>
     */
    public function getValidationPropertyMappings(string $path): array
    {
        return $this->getMapping()->getValidationPropertyMappings($path);
    }

    private function getMapping(): ValidationMappingProviderInterface
    {
        if (null === $this->mapping) {
            $callable = $this->callable;
            $this->mapping = $callable();
        }

        return $this->mapping;
    }
}
