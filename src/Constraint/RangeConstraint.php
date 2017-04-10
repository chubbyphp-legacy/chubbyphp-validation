<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;

final class RangeConstraint implements ConstraintInterface
{
    /**
     * @var int|null
     */
    private $min;

    /**
     * @var int|null
     */
    private $max;

    /**
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct(int $min = null, int $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @param string $path
     * @param mixed $input
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input): array
    {
        if (null === $input) {
            return [];
        }

        if (!is_numeric($input)) {
            return [new Error($path, 'constraint.range.notnumeric', $input)];
        }

        if (null !== $this->min && $input < $this->min || null !== $this->max && $input > $this->max) {
            return [
                new Error($path, 'constraint.range.outofrange', $input, ['min' => $this->min, 'max' => $this->max])
            ];
        }

        return [];
    }
}
