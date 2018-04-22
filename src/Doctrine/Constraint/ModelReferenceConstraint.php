<?php

declare(strict_types=1);

namespace Chubbyphp\ValidationDoctrine\Constraint;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Doctrine\Common\Persistence\Proxy;

final class ModelReferenceConstraint implements ConstraintInterface
{
    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var bool
     */
    private $recursive;

    /**
     * @param bool $nullable
     * @param bool $recursive
     */
    public function __construct(bool $nullable = true, bool $recursive = false)
    {
        $this->nullable = $nullable;
        $this->recursive = $recursive;
    }

    /**
     * @param string                  $path
     * @param mixed                   $input
     * @param ValidatorInterface|null $validator
     *
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input, ValidatorInterface $validator = null): array
    {
        if (null === $input) {
            if (!$this->nullable) {
                return [new Error($path, 'constraint.modelreference.null')];
            }

            return [];
        }

        if ($this->recursive) {
            if (null === $validator) {
                throw new \RuntimeException('Recursive validation is only possible if validator given');
            }

            if ($input instanceof Proxy) {
                $input->__load();
            }

            return $validator->validateObject($input, $path);
        }

        return [];
    }
}
