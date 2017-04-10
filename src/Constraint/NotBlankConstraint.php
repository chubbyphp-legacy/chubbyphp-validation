<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;

final class NotBlankConstraint implements ConstraintInterface
{
    /**
     * @param string $path
     * @param mixed $input
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input): array
    {
        if (null === $input) {
            return [];
        }

        if (!is_string($input)) {
            return [new Error($path, 'constraint.notblank.notstring', $input)];
        }

        if ('' === $input) {
            return [new Error($path, 'constraint.notblank', $input)];
        }

        return [];
    }
}
