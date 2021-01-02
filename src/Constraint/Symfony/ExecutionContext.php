<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Symfony;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class ExecutionContext implements ExecutionContextInterface
{
    private ConstraintViolationList $violations;

    private string $path;

    /**
     * @var mixed
     */
    private $value;

    private ValidatorContextInterface $context;

    /**
     * @param mixed $value
     */
    public function __construct(string $path, $value, ValidatorContextInterface $context)
    {
        $this->violations = new ConstraintViolationList();

        $this->path = $path;
        $this->value = $value;
        $this->context = $context;
    }

    /**
     * @param string       $message
     * @param array<mixed> $parameters
     */
    public function addViolation($message, array $parameters = []): void
    {
        (new ConstraintViolationBuilder($this->violations, $message, $parameters, $this->path))->addViolation();
    }

    /**
     * @param string       $message
     * @param array<mixed> $parameters
     */
    public function buildViolation($message, array $parameters = []): ConstraintViolationBuilderInterface
    {
        return new ConstraintViolationBuilder($this->violations, $message, $parameters, $this->path);
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @return object|null
     */
    public function getObject()
    {
        return null;
    }

    /**
     * @param mixed       $value
     * @param object|null $object
     * @param string      $propertyPath
     */
    public function setNode($value, $object, ?MetadataInterface $metadata, $propertyPath): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string|null $group
     */
    public function setGroup($group): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function setConstraint(Constraint $constraint): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     * @param string $groupHash
     */
    public function markGroupAsValidated($cacheKey, $groupHash): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     * @param string $groupHash
     *
     * @return bool
     */
    public function isGroupValidated($cacheKey, $groupHash)
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     * @param string $constraintHash
     */
    public function markConstraintAsValidated($cacheKey, $constraintHash): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     * @param string $constraintHash
     *
     * @return bool|void
     */
    public function isConstraintValidated($cacheKey, $constraintHash)
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     */
    public function markObjectAsInitialized($cacheKey): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     */
    public function isObjectInitialized($cacheKey): bool
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getViolations(): ConstraintViolationList
    {
        return $this->violations;
    }

    public function getRoot(): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return MetadataInterface|null
     */
    public function getMetadata()
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getGroup(): string
    {
        if ([] === $groups = $this->context->getGroups()) {
            return 'Default';
        }

        return array_shift($groups);
    }

    /**
     * @return string|null
     */
    public function getClassName()
    {
        return null;
    }

    public function getPropertyName(): string
    {
        $pathParts = explode('.', $this->path);

        return array_pop($pathParts);
    }

    /**
     * @param string $subPath
     */
    public function getPropertyPath($subPath = ''): string
    {
        if ('' !== $subPath) {
            $subPath = '.'.$subPath;
        }

        return $this->path.$subPath;
    }

    /**
     * @return array<int, ErrorInterface>
     */
    public function getErrors(): array
    {
        $errors = [];
        /** @var ConstraintViolation $violation */
        foreach ($this->violations as $violation) {
            $errors[] = new Error(
                $violation->getPropertyPath(),
                (string) $violation->getMessage(),
                [
                    'parameters' => $violation->getParameters(),
                    'plural' => $violation->getPlural(),
                    'invalidValue' => $violation->getInvalidValue(),
                    'code' => $violation->getCode(),
                    'cause' => $violation->getCause(),
                ]
            );
        }

        return $errors;
    }
}
