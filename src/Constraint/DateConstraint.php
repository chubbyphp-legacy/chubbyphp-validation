<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class DateConstraint implements ConstraintInterface
{
    /**
     * @param ValidatorInterface $validator,
     * @param string $path
     * @param mixed $input
     * @return ErrorInterface[]
     */
    public function validate(ValidatorInterface $validator, string $path, $input): array
    {
        if (null === $input) {
            return [];
        }

        if ($input instanceof \DateTime) {
            return [];
        }

        if (!is_string($input)) {
            return [new Error($path, 'constraint.date.invalidtype', ['type' => gettype($input)])];
        }

        try {
            new \DateTime($input);
        } catch (\Exception $exception) {
            return [new Error($path, 'constraint.date.notparseable', ['date' => $input])];
        }

        return [];
    }
}
