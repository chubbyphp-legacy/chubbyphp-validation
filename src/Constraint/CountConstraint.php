<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class CountConstraint implements ConstraintInterface
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
     * @param ValidatorInterface $validator
     * @param string $path
     * @param mixed $input
     * @return ErrorInterface[]
     */
    public function validate(ValidatorInterface $validator, string $path, $input): array
    {
        if (null === $input) {
            return [];
        }

        if (!is_array($input) || !$input instanceof \Countable) {
            return [new Error($path, 'constraint.count.notcountable', $input)];
        }

        $count = count($input);

        if (null !== $this->min && $count < $this->min || null !== $this->max && $count > $this->max) {
            return [
                new Error(
                    $path,
                    'constraint.count.outofrange',
                    ['count' => $count, 'min' => $this->min, 'max' => $this->max]
                )
            ];
        }

        return [];
    }
}
