<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class CountConstraint implements ConstraintInterface
{
    private ?int $min = null;

    private ?int $max = null;

    public function __construct(?int $min = null, ?int $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @param mixed $value
     *
     * @return array<ErrorInterface>
     */
    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ?ValidatorInterface $validator = null
    ) {
        if (null === $value) {
            return [];
        }

        if (!\is_array($value) && !$value instanceof \Countable) {
            return [new Error(
                $path,
                'constraint.count.invalidtype',
                ['type' => \is_object($value) ? \get_class($value) : \gettype($value)]
            )];
        }

        $count = \count($value);

        if (null !== $this->min && $count < $this->min || null !== $this->max && $count > $this->max) {
            return [
                new Error(
                    $path,
                    'constraint.count.outofrange',
                    ['count' => $count, 'min' => $this->min, 'max' => $this->max]
                ),
            ];
        }

        return [];
    }
}
