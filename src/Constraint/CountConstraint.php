<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
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

        if (!is_array($value) && !$value instanceof \Countable) {
            return [new Error($path, 'constraint.count.invalidtype', ['type' => gettype($value)])];
        }

        $count = count($value);

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
