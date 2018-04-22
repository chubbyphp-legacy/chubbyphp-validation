<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Validator;

use Chubbyphp\Validation\ValidatorLogicException;
use Chubbyphp\Validation\Mapping\ValidationObjectMappingInterface;

final class ValidatorObjectMappingRegistry implements ValidatorObjectMappingRegistryInterface
{
    /**
     * @var ValidationObjectMappingInterface[]
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
     * @param ValidationObjectMappingInterface $objectMapping
     */
    private function addObjectMapping(ValidationObjectMappingInterface $objectMapping)
    {
        $this->objectMappings[$objectMapping->getClass()] = $objectMapping;
    }

    /**
     * @param string $class
     *
     * @return ValidationObjectMappingInterface
     *
     * @throws ValidatorLogicException
     */
    public function getObjectMapping(string $class): ValidationObjectMappingInterface
    {
        $reflectionClass = new \ReflectionClass($class);

        if (in_array('Doctrine\Common\Persistence\Proxy', $reflectionClass->getInterfaceNames(), true)) {
            $class = $reflectionClass->getParentClass()->name;
        }

        if (isset($this->objectMappings[$class])) {
            return $this->objectMappings[$class];
        }

        throw ValidatorLogicException::createMissingMapping($class);
    }
}
