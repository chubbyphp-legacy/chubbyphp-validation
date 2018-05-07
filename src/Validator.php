<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationGroupsInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Validator\ValidatorContextBuilder;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\Validator\ValidatorObjectMappingRegistryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Validator implements ValidatorInterface
{
    /**
     * @var ValidatorObjectMappingRegistryInterface
     */
    private $validatorObjectMappingRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ValidatorObjectMappingRegistryInterface $validatorObjectMappingRegistry
     * @param LoggerInterface                         $logger
     */
    public function __construct(
        ValidatorObjectMappingRegistryInterface $validatorObjectMappingRegistry,
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
        foreach ($this->validateClass($context, $objectMapping->getValidationClassMapping($path), $path, $object) as $fieldError) {
            $errors[] = $fieldError;
        }

        foreach ($objectMapping->getValidationPropertyMappings($path) as $fieldMapping) {
            foreach ($this->validateField($context, $fieldMapping, $path, $object) as $fieldError) {
                $errors[] = $fieldError;
            }
        }

        return $errors;
    }

    /**
     * @param mixed                     $value
     * @param ConstraintInterface[]     $constraints
     * @param ValidatorContextInterface $context
     *
     * @return @return ErrorInterface[]
     */
    public function validateByConstraints($value, array $constraints, ValidatorContextInterface $context = null)
    {
        $context = $context ?? ValidatorContextBuilder::create()->getContext();

        $errors = [];
        foreach ($constraints as $constraint) {
            foreach ($constraint->validate('', $value, $context, $this) as $error) {
                $errors[] = $error;
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
            return $this->validatorObjectMappingRegistry->getObjectMapping($class);
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

        $this->logger->info('deserialize: path {path}', ['path' => $path]);

        $errors = [];
        foreach ($classMapping->getConstraints() as $constraint) {
            foreach ($constraint->validate($path, $object, $context, $this) as $error) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * @param ValidatorContextInterface          $context
     * @param ValidationPropertyMappingInterface $fieldMapping
     * @param string                             $path
     * @param $object
     *
     * @return ErrorInterface[]
     */
    private function validateField(
        ValidatorContextInterface $context,
        ValidationPropertyMappingInterface $fieldMapping,
        string $path,
        $object
    ): array {
        if (!$this->isWithinGroup($context, $fieldMapping)) {
            return [];
        }

        $name = $fieldMapping->getName();

        $subPath = $this->getSubPathByName($path, $name);

        $this->logger->info('deserialize: path {path}', ['path' => $subPath]);

        $value = $fieldMapping->getAccessor()->getValue($object);

        if (null !== $forceType = $fieldMapping->getForceType()) {
            if (null !== $value && $forceType !== gettype($value)) {
                if (settype($value, $forceType)) {
                    $fieldMapping->getAccessor()->setValue($object, $value);
                } else {
                    // todo
                }
            }
        }

        $errors = [];
        foreach ($fieldMapping->getConstraints() as $constraint) {
            foreach ($constraint->validate($subPath, $value, $context, $this) as $error) {
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
}
