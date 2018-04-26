<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Symfony;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class ExecutionContext implements ExecutionContextInterface
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $violations;

    /**
     * @var Constraint
     */
    private $constraint;

    /**
     * @var string
     */
    private $path;

    /**
     * @var object
     */
    private $object;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param Constraint $constraint
     * @param string     $path
     * @param object     $object
     * @param mixed      $value
     */
    public function __construct(Constraint $constraint, string $path, $object, $value)
    {
        $this->violations = new ConstraintViolationList();

        $this->constraint = $constraint;
        $this->path = $path;
        $this->object = $object;
        $this->value = $value;
    }

    /**
     * @param string $message
     * @param array  $params
     */
    public function addViolation($message, array $params = [])
    {
        (new ConstraintViolationBuilder($this->violations, $message, $params, $this->path))->addViolation();
    }

    /**
     * @param string $message
     * @param array  $parameters
     *
     * @return ConstraintViolationBuilderInterface
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
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @return null|object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed                  $value
     * @param null|object            $object
     * @param MetadataInterface|null $metadata
     * @param string                 $propertyPath
     */
    public function setNode($value, $object, MetadataInterface $metadata = null, $propertyPath)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param null|string $group
     */
    public function setGroup($group)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param Constraint $constraint
     */
    public function setConstraint(Constraint $constraint)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     * @param string $groupHash
     */
    public function markGroupAsValidated($cacheKey, $groupHash)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     * @param string $groupHash
     *
     * @return bool
     */
    public function isGroupValidated($cacheKey, $groupHash)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     * @param string $constraintHash
     */
    public function markConstraintAsValidated($cacheKey, $constraintHash)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     * @param string $constraintHash
     *
     * @return bool|void
     */
    public function isConstraintValidated($cacheKey, $constraintHash)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     */
    public function markObjectAsInitialized($cacheKey)
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @param string $cacheKey
     *
     * @return bool
     */
    public function isObjectInitialized($cacheKey): bool
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function getRoot()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return null|MetadataInterface
     */
    public function getMetadata()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return 'Default';
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return get_class($this->object);
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        $pathParts = explode('.', $this->path);

        return array_pop($pathParts);
    }

    /**
     * @param string $subPath
     *
     * @return string
     */
    public function getPropertyPath($subPath = ''): string
    {
        return $this->path.$subPath;
    }

    /**
     * @return ErrorInterface[]
     */
    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->violations as $violation) {
            $errors[] = new Error(
                $violation->getPropertyPath(),
                $violation->getMessage(),
                [
                    '_parameters' => $violation->getParameters(),
                    '_plural' => $violation->getPlural(),
                    '_invalidValue' => $violation->getInvalidValue(),
                    '_code' => $violation->getCode(),
                ]
            );
        }

        return $errors;
    }
}
