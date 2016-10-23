<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Translation\NullTranslator;
use Chubbyphp\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Validator as v;

final class Validator implements ValidatorInterface
{
    /**
     * @var ValidationHelperInterface[]|array
     */
    private $helpers = [];

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param array                $helpers
     * @param TranslatorInterface  $translator
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        array $helpers = [],
        TranslatorInterface $translator = null,
        LoggerInterface $logger = null
    ) {
        foreach ($helpers as $helper) {
            $this->addHelper($helper);
        }
        $this->translator = $translator ?? new NullTranslator();
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param ValidationHelperInterface $helper
     */
    private function addHelper(ValidationHelperInterface $helper)
    {
        $this->helpers[] = $helper;
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
     * @param ValidatableModelInterface $model
     * @param string                    $locale
     *
     * @return array
     */
    private function assertModelProperties(ValidatableModelInterface $model, string $locale): array
    {
        $errorMessages = [];

        $reflectionClass = new \ReflectionObject($model);
        foreach ($model->getPropertyValidators() as $property => $validator) {
            $value = $this->getPropertyValue($reflectionClass, $model, $property);
            if ([] !== $fieldErrorMessages = $this->assert($validator, $property, $value, $locale)) {
                $errorMessages[$property] = $fieldErrorMessages;
            }
        }

        return $errorMessages;
    }

    /**
     * @param \ReflectionObject $reflection
     * @param ModelInterface    $model
     * @param string            $property
     *
     * @return mixed
     */
    private function getPropertyValue(\ReflectionObject $reflection, ModelInterface $model, string $property)
    {
        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($model);
    }

    /**
     * @param ValidatableModelInterface $model
     * @param string                    $locale
     *
     * @return array
     */
    private function assertValidateModel(ValidatableModelInterface $model, string $locale): array
    {
        if (null === $modelValidator = $model->getModelValidator()) {
            return [];
        }

        $this->runHelpersPerRules($modelValidator->getRules(), $model);

        try {
            $modelValidator->assert($model);
        } catch (NestedValidationException $nestedException) {
            return $this->getValidateModelErrors($nestedException, $locale);
        }

        return [];
    }

    /**
     * @param NestedValidationException $nestedException
     * @param string                    $locale
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
            if ([] !== $fieldErrorMessages = $this->assert($validator, $key, $value, $locale)) {
                $errorMessages[$key] = $fieldErrorMessages;
            }
        }

        return $errorMessages;
    }

    /**
     * @param v      $validator
     * @param string $field
     * @param $value
     * @param string $locale
     *
     * @return array
     */
    private function assert(v $validator, string $field, $value, string $locale)
    {
        $fieldErrorMessages = [];

        $this->runHelpersPerRules($validator->getRules(), $value);

        try {
            $validator->assert($value);
        } catch (NestedValidationException $nestedException) {
            foreach ($nestedException as $exception) {
                $fieldErrorMessages[] = $this->getMessageByException($exception, $field, $value, $locale);
            }
        }

        return $fieldErrorMessages;
    }

    /**
     * @param AbstractRule[]|array $rules
     * @param $value
     */
    private function runHelpersPerRules(array $rules, $value)
    {
        foreach ($rules as $rule) {
            if ($rule instanceof ValidationHelperNeededInterface) {
                $this->runHelpersPerRule($rule, $value);
            }
        }
    }

    /**
     * @param AbstractRule $rule
     * @param $value
     */
    private function runHelpersPerRule(AbstractRule $rule, $value)
    {
        foreach ($this->helpers as $helper) {
            if ($helper->isResponsible($rule, $value)) {
                $helper->help($rule, $value);
            }
        }
    }

    /**
     * @param ValidationException $exception
     * @param string              $field
     * @param string              $locale
     * @param mixed               $value
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
}
