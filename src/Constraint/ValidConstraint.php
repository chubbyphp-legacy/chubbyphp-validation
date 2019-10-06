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
     * @param string                    $path
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @throws ValidatorLogicException
     *
     * @return array<ErrorInterface>
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

        if (null === $validator) {
            throw ValidatorLogicException::createMissingValidator($path);
        }

        if (is_array($value) || $value instanceof \Traversable) {
            $errors = [];
            foreach ($value as $i => $subValue) {
                $errors = array_merge($errors, $validator->validate($subValue, $context, $path.'['.$i.']'));
            }

            return $errors;
        }

        if (is_object($value)) {
            return $validator->validate($value, $context, $path);
        }

        return [new Error(
             $path,
            'constraint.valid.invalidtype',
            ['type' => is_object($value) ? get_class($value) : gettype($value)]
        )];
    }
}
