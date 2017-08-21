<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
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
     * @param string                  $path
     * @param mixed                   $input
     * @param ValidatorInterface|null $validator
     *
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input, ValidatorInterface $validator = null): array
    {
        if (null === $input) {
            return [];
        }

        $this->validInputTypeOrException($input);

        $errors = [];

        $count = count($input);

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

        foreach ($input as $i => $value) {
            foreach ($this->constraints as $constraint) {
                $errors = array_merge($errors, $constraint->validate($path.'['.$i.']', $value, $validator));
            }
        }

        return $errors;
    }

    /**
     * @param array|\Traversable $input
     */
    private function validInputTypeOrException($input)
    {
        if (!is_array($input) && !$input instanceof \Traversable) {
            throw new \RuntimeException(
                sprintf('Invalid input, array or %s needed', \Traversable::class)
            );
        }
    }
}
