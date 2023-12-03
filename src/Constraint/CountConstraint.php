<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class CountConstraint implements ConstraintInterface
{
    public function __construct(private ?int $min = null, private ?int $max = null) {}

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
                ['type' => get_debug_type($value)]
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
