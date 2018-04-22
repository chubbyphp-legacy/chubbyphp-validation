<?php

declare(strict_types=1);

namespace Chubbyphp\ValidationModel\Constraint;

use Chubbyphp\Model\Reference\ModelReferenceInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

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
        $this->validInputTypeOrException($input);

        $model = $input->getModel();

        if (null === $model) {
            if (!$this->nullable) {
                return [new Error($path, 'constraint.modelreference.null')];
            }

            return [];
        }

        if ($this->recursive) {
            if (null === $validator) {
                throw new \RuntimeException('Recursive validation is only possible if validator given');
            }

            return $validator->validateObject($input->getModel(), $path);
        }

        return [];
    }

    /**
     * @param ModelReferenceInterface $input
     */
    private function validInputTypeOrException($input)
    {
        if (!$input instanceof ModelReferenceInterface) {
            throw new \RuntimeException(
                sprintf('Invalid input, instance of %s needed', ModelReferenceInterface::class)
            );
        }
    }
}
