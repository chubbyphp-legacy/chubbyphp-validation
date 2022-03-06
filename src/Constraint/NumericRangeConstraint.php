<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class NumericRangeConstraint implements ConstraintInterface
{
    public function __construct(private ?int $min = null, private ?int $max = null)
    {
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
        if (!is_numeric($value)) {
            return [];
        }

        if (null !== $this->min && $value < $this->min || null !== $this->max && $value > $this->max) {
            return [
                new Error(
                    $path,
                    'constraint.numericrange.outofrange',
                    ['value' => $value, 'min' => $this->min, 'max' => $this->max]
                ),
            ];
        }

        return [];
    }
}
