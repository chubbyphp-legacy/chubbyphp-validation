<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Symfony;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class ConstraintViolationBuilder implements ConstraintViolationBuilderInterface
{
    /**
     * @var ConstraintViolationListInterface<int, ConstraintViolationInterface>
     */
    private ConstraintViolationListInterface $violations;

    private string $path;

    private string $message;

    /**
     * @var array<mixed>
     */
    private array $parameters;

    /**
     * @var mixed
     */
    private $invalidValue;

    private ?int $plural = null;

    private ?string $code = null;

    /**
     * @var mixed
     */
    private $cause;

    /**
     * @param ConstraintViolationListInterface<int, ConstraintViolationInterface> $violations
     * @param array<mixed>                                                        $parameters
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message,
        array $parameters,
        string $path
    ) {
        $this->violations = $violations;

        $this->message = $message;
        $this->parameters = $parameters;
        $this->path = $path;
    }

    /**
     * @return ConstraintViolationBuilder
     */
    public function atPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return ConstraintViolationBuilder
     */
    public function setParameter(string $key, string $value): static
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param array<mixed> $parameters
     *
     * @return ConstraintViolationBuilder
     */
    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return ConstraintViolationBuilder
     */
    public function setTranslationDomain(string $translationDomain): static
    {
        return $this;
    }

    /**
     * @return ConstraintViolationBuilder
     */
    public function setInvalidValue(mixed $invalidValue): static
    {
        $this->invalidValue = $invalidValue;

        return $this;
    }

    /**
     * @return ConstraintViolationBuilder
     */
    public function setPlural(int $number): static
    {
        $this->plural = $number;

        return $this;
    }

    /**
     * @return ConstraintViolationBuilder
     */
    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return ConstraintViolationBuilder
     */
    public function setCause(mixed $cause): static
    {
        $this->cause = $cause;

        return $this;
    }

    public function addViolation(): void
    {
        $this->violations->add(new ConstraintViolation(
            $this->message,
            $this->message,
            $this->parameters,
            null,
            $this->path,
            $this->invalidValue,
            $this->plural,
            $this->code,
            null,
            $this->cause
        ));
    }
}
