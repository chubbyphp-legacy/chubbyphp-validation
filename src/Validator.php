<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Registry\ObjectMappingRegistryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Validator implements ValidatorInterface
{
    /**
     * @var ObjectMappingRegistryInterface
     */
    private $objectMappingRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ObjectMappingRegistryInterface $objectMappingRegistry
     * @param LoggerInterface                $logger
     */
    public function __construct(ObjectMappingRegistryInterface $objectMappingRegistry, LoggerInterface $logger = null)
    {
        $this->objectMappingRegistry = $objectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param object $object
     * @param string $path
     *
     * @return string[]
     */
    public function validateObject($object, string $path = ''): array
    {
        $class = get_class($object);

        $objectMapping = $this->objectMappingRegistry->getObjectMappingForClass($class);

        $errors = array_merge(
            $this->propertyConstraints($objectMapping, $object, $path),
            $this->objectConstraints($objectMapping, $object, $path)
        );

        $this->logErrors($errors);

        return $errors;
    }

    /**
     * @param ObjectMappingInterface $objectMapping
     * @param $object
     * @param string $path
     *
     * @return ErrorInterface[]
     */
    private function propertyConstraints(ObjectMappingInterface $objectMapping, $object, string $path)
    {
        $errors = [];
        foreach ($objectMapping->getPropertyMappings() as $propertyMapping) {
            $property = $propertyMapping->getName();

            $subPath = '' !== $path ? $path.'.'.$property : $property;

            $this->logger->info('validation: path {path}', ['path' => $subPath]);

            $propertyReflection = new \ReflectionProperty($object, $property);
            $propertyReflection->setAccessible(true);

            $propertyValue = $propertyReflection->getValue($object);

            foreach ($propertyMapping->getConstraints() as $constraint) {
                $errors = array_merge($errors, $constraint->validate($subPath, $propertyValue, $this));
            }
        }

        return $errors;
    }

    /**
     * @param ObjectMappingInterface $objectMapping
     * @param $object
     * @param string $path
     *
     * @return ErrorInterface[]
     */
    private function objectConstraints(ObjectMappingInterface $objectMapping, $object, string $path): array
    {
        $errors = [];

        $this->logger->info('validation: path {path}', ['path' => $path]);

        foreach ($objectMapping->getConstraints() as $constraint) {
            $errors = array_merge($errors, $constraint->validate($path, $object, $this));
        }

        return $errors;
    }

    /**
     * @param ErrorInterface[] $errors
     */
    private function logErrors(array $errors)
    {
        foreach ($errors as $error) {
            $this->logError($error);
        }
    }

    /**
     * @param ErrorInterface $error
     */
    private function logError(ErrorInterface $error)
    {
        $this->logger->notice(
            'validation: path {path}, key {key}, arguments {arguments}',
            ['path' => $error->getPath(), 'key' => $error->getKey(), 'arguments' => $error->getArguments()]
        );
    }
}
