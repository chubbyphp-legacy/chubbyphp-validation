<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationGroupsInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Validator implements ValidatorInterface
{
    private ValidationMappingProviderRegistryInterface $validatorObjectMappingRegistry;

    private LoggerInterface $logger;

    public function __construct(
        ValidationMappingProviderRegistryInterface $validatorObjectMappingRegistry,
        ?LoggerInterface $logger = null
    ) {
        $this->validatorObjectMappingRegistry = $validatorObjectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @return array<ErrorInterface>
     */
    public function validate(object $object, ?ValidatorContextInterface $context = null, string $path = '')
    {
        $context ??= ValidatorContextBuilder::create()->getContext();

        $class = \get_class($object);

        $objectMapping = $this->getObjectMapping($class);

        $errors = [];
        foreach ($this->validateClass(
            $context,
            $objectMapping->getValidationClassMapping($path),
            $path,
            $object
        ) as $classError) {
            $errors[] = $classError;
        }

        foreach ($objectMapping->getValidationPropertyMappings($path) as $propertyMapping) {
            foreach ($this->validateProperty($context, $propertyMapping, $path, $object) as $propertyError) {
                $errors[] = $propertyError;
            }
        }

        return $errors;
    }

    /**
     * @throws ValidatorLogicException
     */
    private function getObjectMapping(string $class): ValidationMappingProviderInterface
    {
        try {
            return $this->validatorObjectMappingRegistry->provideMapping($class);
        } catch (ValidatorLogicException $exception) {
            $this->logger->error('validate: {exception}', ['exception' => $exception->getMessage()]);

            throw $exception;
        }
    }

    /**
     * @return array<ErrorInterface>
     */
    private function validateClass(
        ValidatorContextInterface $context,
        ?ValidationClassMappingInterface $classMapping,
        string $path,
        object $object
    ) {
        if (null === $classMapping) {
            return [];
        }

        if (!$this->isWithinGroup($context, $classMapping)) {
            return [];
        }

        $this->logPath($path);

        $errors = [];
        foreach ($classMapping->getConstraints() as $constraint) {
            $this->logConstraint($path, $constraint);

            foreach ($constraint->validate($path, $object, $context, $this) as $error) {
                $this->logError($path, $constraint, $error);

                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * @return array<ErrorInterface>
     */
    private function validateProperty(
        ValidatorContextInterface $context,
        ValidationPropertyMappingInterface $propertyMapping,
        string $path,
        object $object
    ): array {
        if (!$this->isWithinGroup($context, $propertyMapping)) {
            return [];
        }

        $name = $propertyMapping->getName();

        $subPath = $this->getSubPathByName($path, $name);

        $this->logPath($subPath);

        $value = $propertyMapping->getAccessor()->getValue($object);

        $errors = [];
        foreach ($propertyMapping->getConstraints() as $constraint) {
            $this->logConstraint($subPath, $constraint);

            foreach ($constraint->validate($subPath, $value, $context, $this) as $error) {
                $this->logError($subPath, $constraint, $error);

                $errors[] = $error;
            }
        }

        return $errors;
    }

    private function isWithinGroup(
        ValidatorContextInterface $context,
        ValidationGroupsInterface $mapping
    ): bool {
        if ([] === $groups = $context->getGroups()) {
            return true;
        }

        foreach ($mapping->getGroups() as $group) {
            if (\in_array($group, $groups, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int|string $name
     */
    private function getSubPathByName(string $path, $name): string
    {
        return '' === $path ? (string) $name : $path.'.'.$name;
    }

    private function logPath(string $path): void
    {
        $this->logger->info('validate: path {path}', ['path' => $path]);
    }

    private function logConstraint(string $path, ConstraintInterface $constraint): void
    {
        $this->logger->debug('validate: path {path}, constraint {constraint}', [
            'path' => $path,
            'constraint' => \get_class($constraint),
        ]);
    }

    private function logError(string $path, ConstraintInterface $constraint, ErrorInterface $error): void
    {
        $this->logger->notice('validate: path {path}, constraint {constraint}, error {error}', [
            'path' => $path,
            'constraint' => \get_class($constraint),
            'error' => [
                'key' => $error->getKey(),
                'arguments' => $error->getArguments(),
            ],
        ]);
    }
}
