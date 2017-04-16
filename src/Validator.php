<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Registry\ObjectMappingRegistry;

final class Validator implements ValidatorInterface
{
    /**
     * @var ObjectMappingRegistry
     */
    private $objectMappingRegistry;

    /**
     * @param ObjectMappingRegistry $objectMappingRegistry
     */
    public function __construct(ObjectMappingRegistry $objectMappingRegistry)
    {
        $this->objectMappingRegistry = $objectMappingRegistry;
    }

    /**
     * @param object $object
     * @param string $path
     * @return string[]
     */
    public function validateObject($object, string $path = ''): array
    {
        $class = get_class($object);

        $objectMapping = $this->objectMappingRegistry->getObjectMappingForClass($class);

        $errors = [];

        foreach ($objectMapping->getPropertyMappings() as $propertyMapping) {
            $property = $propertyMapping->getName();

            $propertyReflection = new \ReflectionProperty($class, $property);
            $propertyReflection->setAccessible(true);

            $propertyValue = $propertyReflection->getValue($object);

            $subPath = '' !== $path ? $path . '.' . $property : $property;

            foreach ($propertyMapping->getConstraints() as $constraint) {
                $errors = array_merge($errors, $constraint->validate($this, $subPath, $propertyValue));
            }
        }

        foreach ($objectMapping->getConstraints() as $constraint) {
            $errors = array_merge($errors, $constraint->validate($this, $path, $object));
        }

        return $errors;
    }
}
