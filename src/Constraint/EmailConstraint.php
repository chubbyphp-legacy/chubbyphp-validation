<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class EmailConstraint implements ConstraintInterface
{
    /**
     * @param string $path
     * @param mixed $input
     * @param ValidatorInterface $validator
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input, ValidatorInterface $validator = null): array
    {
        if (null === $input) {
            return [];
        }

        if (!is_string($input)) {
            return [new Error($path, 'constraint.email.invalidtype', ['type' => gettype($input)])];
        }

        if (!preg_match('/^.+\@\S+\.\S+$/', $input)) {
            return [new Error($path, 'constraint.email.invalidformat', ['value' => $input])];
        }

        return [];
    }
}
