<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class NumericConstraint implements ConstraintInterface
{
    /**
     * @param string                    $path
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @return array<ErrorInterface>
     */
    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        if (null === $value || '' === $value) {
            return [];
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            return [new Error(
                $path,
                'constraint.numeric.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        $value = (string) $value;

        if (!is_numeric($value)) {
            return [new Error($path, 'constraint.numeric.notnumeric', ['value' => $value])];
        }

        return [];
    }
}
