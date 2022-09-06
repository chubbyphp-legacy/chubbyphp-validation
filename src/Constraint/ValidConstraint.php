<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;

final class ValidConstraint implements ConstraintInterface
{
    /**
     * @param mixed $value
     *
     * @return array<ErrorInterface>
     *
     * @throws ValidatorLogicException
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

        if (null === $validator) {
            throw ValidatorLogicException::createMissingValidator($path);
        }

        if (is_iterable($value)) {
            $errors = [];
            foreach ($value as $i => $subValue) {
                $errors = array_merge($errors, $validator->validate($subValue, $context, $path.'['.$i.']'));
            }

            return $errors;
        }

        if (\is_object($value)) {
            return $validator->validate($value, $context, $path);
        }

        return [new Error(
            $path,
            'constraint.valid.invalidtype',
            ['type' => get_debug_type($value)]
        )];
    }
}
