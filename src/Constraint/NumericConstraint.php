<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class NumericConstraint implements ConstraintInterface
{
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

        if (!is_scalar($value)) {
            return [new Error($path, 'constraint.numeric.invalidtype', ['type' => gettype($value)])];
        }

        if (!is_numeric($value)) {
            return [new Error($path, 'constraint.numeric.notnumeric', ['value' => $value])];
        }

        return [];
    }
}
