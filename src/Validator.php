<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Error\ErrorInterface;
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
     * @param LoggerInterface $logger
     */
    public function __construct(
        ValidatorObjectMappingRegistryInterface $validatorObjectMappingRegistry,
        LoggerInterface $logger = null
    ) {
        $this->validatorObjectMappingRegistry = $validatorObjectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param object $object
     * @param ValidatorContextInterface $context
     * @param string                       $path
     *
     * @return ErrorInterface[]
     */
    public function validate($object, ValidatorContextInterface $context = null, string $path = '')
    {
        $context = $context ?? ValidatorContextBuilder::create()->getContext();

        $class = is_object($object) ? get_class($object) : $object;
        $objectMapping = $this->getObjectMapping($class);
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
}
