<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Symfony;

use Chubbyphp\Validation\Error\ErrorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class ExecutionContext implements ExecutionContextInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var ErrorInterface[]
     */
    private $errors;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function addViolation($message, array $params = [])
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $message
     * @param array  $parameters
     *
     * @return ConstraintViolationBuilderInterface
     */
    public function buildViolation($message, array $parameters = []): ConstraintViolationBuilderInterface
    {
        return new ConstraintViolationBuilder($this, $message, $parameters, $this->path);
    }

    public function getValidator()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getObject()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function setNode($value, $object, MetadataInterface $metadata = null, $propertyPath)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function setGroup($group)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function setConstraint(Constraint $constraint)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function markGroupAsValidated($cacheKey, $groupHash)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function isGroupValidated($cacheKey, $groupHash)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function markConstraintAsValidated($cacheKey, $constraintHash)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function isConstraintValidated($cacheKey, $constraintHash)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function markObjectAsInitialized($cacheKey)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function isObjectInitialized($cacheKey)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getViolations()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getRoot()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getValue()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getMetadata()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getGroup()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getClassName()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getPropertyName()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    public function getPropertyPath($subPath = '')
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param ErrorInterface $error
     */
    public function addError(ErrorInterface $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return ErrorInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
