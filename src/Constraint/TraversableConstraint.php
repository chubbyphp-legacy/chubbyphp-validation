<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class TraversableConstraint implements ConstraintInterface
{
    /**
     * @var ConstraintInterface[]
     */
    private $constraints;

    /**
     * @var int|null
     */
    private $min;

    /**
     * @var int|null
     */
    private $max;

    /**
     * @param ConstraintInterface[] $constraints
     * @param int|null              $min
     * @param int|null              $max
     */
    public function __construct(array $constraints = [], int $min = null, int $max = null)
    {
        $this->constraints = $constraints;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @param string                    $path
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @return ErrorInterface[]
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

        $this->validInputTypeOrException($value);

        $errors = [];

        $count = count($value);

        if (null !== $this->min && $count < $this->min || null !== $this->max && $count > $this->max) {
            $errors[] = new Error(
                 $path.'[_all]',
                'constraint.traversable.outofrange',
                ['count' => $count, 'min' => $this->min, 'max' => $this->max]
            );
        }

        if (null === $validator) {
            throw new \RuntimeException('Recursive validation is only possible if validator given');
        }

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

    /**
     * @param array|\Traversable $value
     */
    private function validInputTypeOrException($value)
    {
        if (!is_array($value) && !$value instanceof \Traversable) {
            throw new \RuntimeException(
                sprintf('Invalid value, array or %s needed', \Traversable::class)
            );
        }
    }
}
