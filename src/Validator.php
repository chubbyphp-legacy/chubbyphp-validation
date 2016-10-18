<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Translation\NullTranslator;
use Chubbyphp\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;
use Chubbyphp\Validation\Rules\UniqueModelRule;

final class Validator implements ValidatorInterface
{
    /**
     * @var RepositoryInterface[]|array
     */
    private $repositories = [];

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param array                $repositories
     * @param TranslatorInterface  $translator
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        array $repositories = [],
        TranslatorInterface $translator = null,
        LoggerInterface $logger = null
    ) {
        foreach ($repositories as $repository) {
            $this->addRepository($repository);
        }
        $this->translator = $translator ?? new NullTranslator();
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
     * @param string                    $locale
     *
     * @return array
     */
    public function validateModel(ValidatableModelInterface $model, string $locale = 'de'): array
    {
        $errorMessagesFromProperties = $this->assertModelProperties($model, $locale);
        $errorMessagesFromModel = $this->assertValidateModel($model, $locale);

        return array_merge_recursive($errorMessagesFromProperties, $errorMessagesFromModel);
    }

    /**
     * @return array
     */
    private function assertModelProperties(ValidatableModelInterface $model, string $locale): array
    {
        $reflectionClass = new \ReflectionObject($model);

        $errorMessages = [];
        foreach ($model->getPropertyValidators() as $property => $validator) {
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $value = $reflectionProperty->getValue($model);
            try {
                $validator->assert($value);
            } catch (NestedValidationException $nestedException) {
                foreach ($nestedException as $exception) {
                    if (!isset($errorMessages[$property])) {
                        $errorMessages[$property] = [];
                    }

                    $errorMessages[$property][] = $this->getMessageByException($exception, $property, $value, $locale);
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
    private function assertValidateModel(ValidatableModelInterface $model, string $locale): array
    {
        if (null === $modelValidator = $model->getModelValidator()) {
            return [];
        }

        try {
            $this->assignRepositoryToRules(get_class($model), $modelValidator->getRules());
            $modelValidator->assert($model);
        } catch (NestedValidationException $exception) {
            return $this->getValidateModelErrors($exception, $locale);
        }

        return [];
    }

    /**
     * @param NestedValidationException $nestedException
     *
     * @return array
     */
    private function getValidateModelErrors(NestedValidationException $nestedException, string $locale): array
    {
        $errorMessages = [];
        foreach ($nestedException as $exception) {
            /** @var ValidationException $exception */
            $properties = $exception->hasParam('properties') ? $exception->getParam('properties') : ['__model'];
            foreach ($properties as $property) {
                if (!isset($errorMessages[$property])) {
                    $errorMessages[$property] = [];
                }

                $errorMessages[$property][] = $this->getMessageByException($exception, $property, '', $locale);
            }
        }

        return $errorMessages;
    }

    /**
     * @param array  $data
     * @param array  $validators
     * @param string $locale
     *
     * @return array
     */
    public function validateArray(array $data, array $validators, string $locale = 'de'): array
    {
        $errorMessages = [];
        foreach ($validators as $key => $validator) {
            $value = $data[$key] ?? null;
            try {
                $validator->assert($value);
            } catch (NestedValidationException $nestedException) {
                foreach ($nestedException as $exception) {
                    if (!isset($errorMessages[$key])) {
                        $errorMessages[$key] = [];
                    }
                    $errorMessages[$key][] = $this->getMessageByException($exception, $key, $value, $locale);
                }
            }
        }

        return $errorMessages;
    }

    /**
     * @param ValidationException $exception
     * @param string              $field
     * @param string              $locale
     * @param null                $value
     *
     * @return string
     */
    private function getMessageByException(
        ValidationException $exception,
        string $field,
        $value,
        string $locale
    ): string {
        $exception->setParam('translator', function ($key) use ($exception, $locale) {
            return $this->translator->translate($locale, $key);
        });

        $message = $exception->getMainMessage();

        $this->logger->notice(
            'validation: field {field}, value {value}, message {message}',
            ['field' => $field, 'value' => $value, 'message' => $message]
        );

        return $message;
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
