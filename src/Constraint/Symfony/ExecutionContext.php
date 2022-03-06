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

    /**
     * @param mixed $value
     */
    public function __construct(private string $path, private $value, private ValidatorContextInterface $context)
    {
        $this->violations = new ConstraintViolationList();
    }

    /**
     * @param array<mixed> $parameters
     */
    public function addViolation(string $message, array $parameters = []): void
    {
        (new ConstraintViolationBuilder($this->violations, $message, $parameters, $this->path))->addViolation();
    }

    /**
     * @param array<mixed> $parameters
     */
    public function buildViolation(string $message, array $parameters = []): ConstraintViolationBuilderInterface
    {
        return new ConstraintViolationBuilder($this->violations, $message, $parameters, $this->path);
    }

    public function getValidator(): ValidatorInterface
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getObject(): ?object
    {
        return null;
    }

    public function setNode(mixed $value, ?object $object, ?MetadataInterface $metadata, string $propertyPath): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param null|string $group
     */
    public function setGroup($group): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function setConstraint(Constraint $constraint): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function markGroupAsValidated(string $cacheKey, string $groupHash): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function isGroupValidated(string $cacheKey, string $groupHash): bool
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function markConstraintAsValidated(string $cacheKey, string $constraintHash): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function isConstraintValidated(string $cacheKey, string $constraintHash): bool
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function markObjectAsInitialized(string $cacheKey): void
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function isObjectInitialized(string $cacheKey): bool
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getViolations(): ConstraintViolationList
    {
        return $this->violations;
    }

    public function getRoot(): mixed
    {
        throw new NotImplementedException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getMetadata(): MetadataInterface
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

    public function getClassName(): ?string
    {
        return null;
    }

    public function getPropertyName(): string
    {
        $pathParts = explode('.', $this->path);

        return array_pop($pathParts);
    }

    public function getPropertyPath(string $subPath = ''): string
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
