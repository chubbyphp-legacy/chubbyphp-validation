<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Symfony;

use Chubbyphp\Validation\Error\ErrorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

final class ConstraintViolation implements ConstraintViolationInterface
{
    /**
     * @var ErrorInterface
     */
    private $error;

    /**
     * @param ErrorInterface $error
     */
    public function __construct(ErrorInterface $error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->error->getKey();
    }

    /**
     * @return string
     */
    public function getMessageTemplate(): string
    {
        return $this->error->getKey();
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->error->getArguments()['_parameters'] ?? [];
    }

    /**
     * @return int|null
     */
    public function getPlural()
    {
        return $this->error->getArguments()['_plural'] ?? null;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        throw new \RuntimeException(sprintf('Method "%s" is not implemented', __METHOD__));
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return $this->error->getPath();
    }

    /**
     * @return mixed|null
     */
    public function getInvalidValue()
    {
        return $this->error->getArguments()['_invalidValue'] ?? null;
    }

    public function getCode()
    {
        return $this->error->getArguments()['_code'] ?? null;
    }
}
