<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\ValidatorLogicException;

final class ValidationMappingProviderRegistry implements ValidationMappingProviderRegistryInterface
{
    /**
     * @var ValidationMappingProviderInterface[]
     */
    private $objectMappings;

    /**
     * @param array $objectMappings
     */
    public function __construct(array $objectMappings)
    {
        $this->objectMappings = [];
        foreach ($objectMappings as $objectMapping) {
            $this->addObjectMapping($objectMapping);
        }
    }

    /**
     * @param string $class
     *
     * @throws ValidatorLogicException
     *
     * @return ValidationMappingProviderInterface
     */
    public function provideMapping(string $class): ValidationMappingProviderInterface
    {
        $reflectionClass = new \ReflectionClass($class);

        if (in_array('Doctrine\Common\Persistence\Proxy', $reflectionClass->getInterfaceNames(), true)) {
            /** @var \ReflectionClass $parentReflectionClass */
            $parentReflectionClass = $reflectionClass->getParentClass();
            $class = $parentReflectionClass->getName();
        }

        if (isset($this->objectMappings[$class])) {
            return $this->objectMappings[$class];
        }

        throw ValidatorLogicException::createMissingMapping($class);
    }

    /**
     * @param ValidationMappingProviderInterface $objectMapping
     */
    private function addObjectMapping(ValidationMappingProviderInterface $objectMapping): void
    {
        $this->objectMappings[$objectMapping->getClass()] = $objectMapping;
    }
}
