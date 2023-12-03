<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Symfony;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class ConstraintViolationBuilder implements ConstraintViolationBuilderInterface
{
    private mixed $invalidValue = null;

    private ?int $plural = null;

    private ?string $code = null;

    private mixed $cause = null;

    /**
     * @param ConstraintViolationListInterface<int, ConstraintViolationInterface> $violations
     * @param array<mixed>                                                        $parameters
     */
    public function __construct(
        private ConstraintViolationListInterface $violations,
        private string $message,
        private array $parameters,
        private string $path
    ) {}

    public function atPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function setParameter(string $key, string $value): static
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param array<mixed> $parameters
     */
    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function disableTranslation(): static
    {
        return $this;
    }

    public function setTranslationDomain(string $translationDomain): static
    {
        return $this;
    }

    public function setInvalidValue(mixed $invalidValue): static
    {
        $this->invalidValue = $invalidValue;

        return $this;
    }

    public function setPlural(int $number): static
    {
        $this->plural = $number;

        return $this;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

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
