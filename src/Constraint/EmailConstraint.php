<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class EmailConstraint implements ConstraintInterface
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

        if (!is_string($input)) {
            return [new Error($path, 'constraint.email.notstring', ['type' => gettype($input)])];
        }

        if (!preg_match('/^.+\@\S+\.\S+$/', $input)) {
            return [new Error($path, 'constraint.email.invalid')];
        }

        return [];
    }
}
