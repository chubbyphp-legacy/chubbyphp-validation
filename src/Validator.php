<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationGroupsInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Validator implements ValidatorInterface
{
    /**
     * @var \Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistryInterface
     */
    private $validatorObjectMappingRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ValidationMappingProviderRegistryInterface $validatorObjectMappingRegistry
     * @param LoggerInterface                            $logger
     */
    public function __construct(
        ValidationMappingProviderRegistryInterface $validatorObjectMappingRegistry,
        LoggerInterface $logger = null
    ) {
        $this->validatorObjectMappingRegistry = $validatorObjectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param object                    $object
     * @param ValidatorContextInterface $context
     * @param string                    $path
     *
     * @return ErrorInterface[]
     */
    public function validate($object, ValidatorContextInterface $context = null, string $path = '')
    {
        $context = $context ?? ValidatorContextBuilder::create()->getContext();

        $class = is_object($object) ? get_class($object) : $object;

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
     * @param string $class
     *
     * @return ValidationMappingProviderInterface
     *
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
     * @param ValidatorContextInterface            $context
     * @param ValidationClassMappingInterface|null $classMapping
     * @param string                               $path
     * @param                                      $object
     *
     * @return ErrorInterface[]
     */
    private function validateClass(
        ValidatorContextInterface $context,
        ValidationClassMappingInterface $classMapping = null,
        string $path,
        $object
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
     * @param ValidatorContextInterface          $context
     * @param ValidationPropertyMappingInterface $propertyMapping
     * @param string                             $path
     * @param $object
     *
     * @return ErrorInterface[]
     */
    private function validateProperty(
        ValidatorContextInterface $context,
        ValidationPropertyMappingInterface $propertyMapping,
        string $path,
        $object
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

    /**
     * @param ValidatorContextInterface $context
     * @param ValidationGroupsInterface $mapping
     *
     * @return bool
     */
    private function isWithinGroup(
        ValidatorContextInterface $context,
        ValidationGroupsInterface $mapping
    ): bool {
        if ([] === $groups = $context->getGroups()) {
            return true;
        }

        foreach ($mapping->getGroups() as $group) {
            if (in_array($group, $groups, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string     $path
     * @param string|int $name
     *
     * @return string
     */
    private function getSubPathByName(string $path, $name): string
    {
        return '' === $path ? (string) $name : $path.'.'.$name;
    }

    /**
     * @param string $path
     */
    private function logPath(string $path)
    {
        $this->logger->info('deserialize: path {path}', ['path' => $path]);
    }

    /**
     * @param string              $path
     * @param ConstraintInterface $constraint
     */
    private function logConstraint(string $path, ConstraintInterface $constraint)
    {
        $this->logger->debug('deserialize: path {path}, constraint {constraint}', [
            'path' => $path,
            'constraint' => get_class($constraint),
        ]);
    }

    /**
     * @param string              $path
     * @param ConstraintInterface $constraint
     * @param ErrorInterface      $error
     */
    private function logError(string $path, ConstraintInterface $constraint, ErrorInterface $error)
    {
        $this->logger->notice('deserialize: path {path}, constraint {constraint}, error {error}', [
            'path' => $path,
            'constraint' => get_class($constraint),
            'error' => [
                'key' => $error->getKey(),
                'arguments' => $error->getArguments(),
            ],
        ]);
    }
}
