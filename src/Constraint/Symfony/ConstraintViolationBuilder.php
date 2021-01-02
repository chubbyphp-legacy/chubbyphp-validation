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
    private $violations;

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
     * @param string $path
     *
     * @return ConstraintViolationBuilder
     */
    public function atPath($path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return ConstraintViolationBuilder
     */
    public function setParameter($key, $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param array<mixed> $parameters
     *
     * @return ConstraintViolationBuilder
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param string $translationDomain
     *
     * @return ConstraintViolationBuilder
     */
    public function setTranslationDomain($translationDomain): self
    {
        return $this;
    }

    /**
     * @param mixed $invalidValue
     *
     * @return ConstraintViolationBuilder
     */
    public function setInvalidValue($invalidValue): self
    {
        $this->invalidValue = $invalidValue;

        return $this;
    }

    /**
     * @param int $number
     *
     * @return ConstraintViolationBuilder
     */
    public function setPlural($number): self
    {
        $this->plural = $number;

        return $this;
    }

    /**
     * @param string|null $code
     *
     * @return ConstraintViolationBuilder
     */
    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param mixed $cause
     *
     * @return ConstraintViolationBuilder
     */
    public function setCause($cause): self
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
