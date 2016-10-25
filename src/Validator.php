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
     * @var RequirementInterface[]|array
     */
    private $requirements = [];

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param array                $requirements
     * @param TranslatorInterface  $translator
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        array $requirements = [],
        TranslatorInterface $translator = null,
        LoggerInterface $logger = null
    ) {
        foreach ($requirements as $requirement) {
            $this->addRequirement($requirement);
        }
        $this->translator = $translator ?? new NullTranslator();
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param RequirementInterface $requirement
     */
    private function addRequirement(RequirementInterface $requirement)
    {
        if (!isset($this->requirements[$requirement->provides()])) {
            $this->requirements[$requirement->provides()] = [];
        }

        $this->requirements[$requirement->provides()][] = $requirement;
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

        $this->setRequirementsPerRules($modelValidator->getRules(), $model);

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
     * @param mixed  $value
     * @param string $locale
     *
     * @return array
     */
    private function assert(v $validator, string $field, $value, string $locale)
    {
        $fieldErrorMessages = [];

        $this->setRequirementsPerRules($validator->getRules(), $value);

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
     * @param mixed                $value
     */
    private function setRequirementsPerRules(array $rules, $value)
    {
        foreach ($rules as $rule) {
            $this->setRequirementsPerRule($rule, $value);
        }
    }

    /**
     * @param AbstractRule $rule
     * @param mixed        $value
     */
    private function setRequirementsPerRule(AbstractRule $rule, $value)
    {
        if (!$rule instanceof LazyRequirementInterface) {
            return;
        }

        foreach ($rule->requires() as $require) {
            $this->setRequirementPerRule($rule, $require, $value);
        }
    }

    /**
     * @param LazyRequirementInterface $rule
     * @param string                   $require
     * @param mixed                    $value
     */
    private function setRequirementPerRule(LazyRequirementInterface $rule, string $require, $value)
    {
        foreach ($this->getRequirements($require) as $requirement) {
            if ($requirement->isResponsible($value)) {
                $rule->setRequirement($require, $requirement->getRequirement());
            }
        }
    }

    /**
     * @param string $require
     *
     * @return RequirementInterface[]|array
     */
    private function getRequirements(string $require): array
    {
        if (isset($this->requirements[$require])) {
            return $this->requirements[$require];
        }

        return [];
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
