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

        if ($value instanceof \DateTimeInterface) {
            return [];
        }

        if (!is_string($value)) {
            return [new Error(
                $path,
                'constraint.date.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        $trimmedValue = trim($value);

        try {
            new \DateTime($trimmedValue);
        } catch (\Exception $exception) {
            error_clear_last();

            $dateTimeErrors = \DateTime::getLastErrors();

            $messages = [];
            foreach ($dateTimeErrors['errors'] as $position => $message) {
                if (!isset($messages[$message])) {
                    $messages[$message] = [];
                }

                $messages[$message][] = $position;
            }

            $errors = [];
            foreach ($messages as $message => $positions) {
                $errors[] = new Error(
                    $path,
                    'constraint.date.error',
                    ['message' => $message, 'positions' => $positions, 'value' => $value]
                );
            }

            return $errors;
        }

        return [];
    }
}
