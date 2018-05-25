<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;

final class AllConstraint implements ConstraintInterface
{
    /**
     * @var ConstraintInterface[]
     */
    private $constraints;

    /**
     * @param ConstraintInterface[] $constraints
     */
    public function __construct(array $constraints = [])
    {
        $this->constraints = $constraints;
    }

    /**
     * @param string                    $path
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @return ErrorInterface[]
     *
     * @throws ValidatorLogicException
     */
    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        if (null === $value) {
            return [];
        }

        if (!is_array($value) && !$value instanceof \Traversable) {
            return [new Error(
                $path.'[_all]',
                'constraint.all.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        $errors = [];
        foreach ($value as $i => $subValue) {
            foreach ($this->constraints as $constraint) {
                $errors = array_merge(
                    $errors,
                    $constraint->validate($path.'['.$i.']', $subValue, $context, $validator)
                );
            }
        }

        return $errors;
    }
}
