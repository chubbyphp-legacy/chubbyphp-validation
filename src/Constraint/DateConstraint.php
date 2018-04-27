<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class DateConstraint implements ConstraintInterface
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

        if ($value instanceof \DateTime) {
            return [];
        }

        if (!is_string($value)) {
            return [new Error(
                $path,
                'constraint.date.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        try {
            new \DateTime($value);
        } catch (\Exception $exception) {
            error_clear_last();

            return [new Error($path, 'constraint.date.invalidvalue', ['value' => $value])];
        }

        return [];
    }
}
