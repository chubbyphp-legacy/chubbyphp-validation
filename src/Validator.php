<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Model\RepositoryInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Chubbyphp\Validation\Rules\UniqueModelRule;

final class Validator implements ValidatorInterface
{
    /**
     * @var RepositoryInterface[]|array
     */
    private $repositories = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param array                $repositories
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $repositories = [], LoggerInterface $logger = null)
    {
        foreach ($repositories as $repository) {
            $this->addRepository($repository);
        }
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param RepositoryInterface $repository
     */
    private function addRepository(RepositoryInterface $repository)
    {
        $this->repositories[$repository->getModelClass()] = $repository;
    }

    /**
     * @param ValidatableModelInterface $model
     *
     * @return array
     */
    public function validateModel(ValidatableModelInterface $model): array
    {
        $errorMessagesFromProperties = $this->assertModelProperties($model);
        $errorMessagesFromModel = $this->assertValidateModel($model);

        return array_merge_recursive($errorMessagesFromProperties, $errorMessagesFromModel);
    }

    /**
     * @return array
     */
    private function assertModelProperties(ValidatableModelInterface $model): array
    {
        $reflectionClass = new \ReflectionObject($model);

        $errorMessages = [];
        foreach ($model->getPropertyValidators() as $property => $validator) {
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $value = $reflectionProperty->getValue($model);
            try {
                $validator->assert($value);
            } catch (NestedValidationException $exception) {
                $messages = $exception->getMessages();
                foreach ($messages as $message) {
                    if (!isset($errorMessages[$property])) {
                        $errorMessages[$property] = [];
                    }
                    $errorMessages[$property][] = $message;
                    $this->logger->notice(
                        'validation: property {property}, value {value}, message {message}',
                        ['property' => $property, 'value' => $value, 'message' => $message]
                    );
                }
            }
        }

        return $errorMessages;
    }

    /**
     * @param ValidatableModelInterface $model
     *
     * @return array
     */
    private function assertValidateModel(ValidatableModelInterface $model): array
    {
        if (null === $modelValidator = $model->getModelValidator()) {
            return [];
        }

        try {
            $this->assignRepositoryToRules(get_class($model), $modelValidator->getRules());
            $modelValidator->assert($model);
        } catch (NestedValidationException $exception) {
            return $this->getValidateModelErrors($exception);
        }

        return [];
    }

    /**
     * @param NestedValidationException $exception
     *
     * @return array
     */
    private function getValidateModelErrors(NestedValidationException $exception): array
    {
        $errorMessages = [];
        foreach ($exception as $ruleException) {
            $message = $ruleException->getMainMessage();
            $properties = $ruleException->hasParam('properties') ? $ruleException->getParam('properties') : ['__model'];
            foreach ($properties as $property) {
                if (!isset($errorMessages[$property])) {
                    $errorMessages[$property] = [];
                }
                $errorMessages[$property][] = $message;
                $this->logger->notice(
                    'validation: property {property}, message {message}',
                    ['property' => $property, 'message' => $message]
                );
            }
        }

        return $errorMessages;
    }

    /**
     * @param array $data
     * @param array $validators
     *
     * @return array
     */
    public function validateArray(array $data, array $validators): array
    {
        $errorMessages = [];
        foreach ($validators as $key => $validator) {
            $value = $data[$key] ?? null;
            try {
                $validator->assert($value);
            } catch (NestedValidationException $exception) {
                $messages = $exception->getMessages();
                foreach ($messages as $message) {
                    if (!isset($errorMessages[$key])) {
                        $errorMessages[$key] = [];
                    }
                    $errorMessages[$key][] = $message;
                    $this->logger->notice(
                        'validation: key {key}, value {value}, message {message}',
                        ['key' => $key, 'value' => $value, 'message' => $message]
                    );
                }
            }
        }

        return $errorMessages;
    }

    /**
     * @param string $modelClass
     * @param array  $rules
     */
    private function assignRepositoryToRules(string $modelClass, array $rules)
    {
        foreach ($rules as $rule) {
            $this->assignRepositoryToRule($modelClass, $rule);
        }
    }

    /**
     * @param string      $modelClass
     * @param Validatable $rule
     */
    private function assignRepositoryToRule(string $modelClass, Validatable $rule)
    {
        if ($rule instanceof UniqueModelRule && isset($this->repositories[$modelClass])) {
            $rule->setRepository($this->repositories[$modelClass]);
        }
    }
}
