<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Mapping\ValidationFieldMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationObjectMappingInterface;
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
        foreach ($objectMapping->getValidationFieldMappings($path) as $fieldMapping) {
            foreach ($this->validateField($context, $fieldMapping, $path, $object) as $fieldError) {
                $errors[] = $fieldError;
            }
        }

        return $errors;
    }

    /**
     * @param string $class
     *
     * @return ValidationObjectMappingInterface
     *
     * @throws ValidatorLogicException
     */
    private function getObjectMapping(string $class): ValidationObjectMappingInterface
    {
        try {
            return $this->validatorObjectMappingRegistry->getObjectMapping($class);
        } catch (ValidatorLogicException $exception) {
            $this->logger->error('validate: {exception}', ['exception' => $exception->getMessage()]);

            throw $exception;
        }
    }

    /**
     * @param ValidatorContextInterface       $context
     * @param ValidationFieldMappingInterface $fieldMapping
     * @param string                          $path
     * @param $object
     *
     * @return ErrorInterface[]
     */
    private function validateField(
        ValidatorContextInterface $context,
        ValidationFieldMappingInterface $fieldMapping,
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

        $errors = [];
        foreach ($fieldMapping->getConstraints() as $constraint) {
            foreach ($constraint->validate($subPath, $value, $context, $this) as $error) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * @param ValidatorContextInterface       $context
     * @param ValidationFieldMappingInterface $fieldMapping
     *
     * @return bool
     */
    private function isWithinGroup(
        ValidatorContextInterface $context,
        ValidationFieldMappingInterface $fieldMapping
    ): bool {
        if ([] === $groups = $context->getGroups()) {
            return true;
        }

        foreach ($fieldMapping->getGroups() as $group) {
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
