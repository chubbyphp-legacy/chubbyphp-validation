<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class NotBlankConstraint implements ConstraintInterface
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

        if ('' === $input || [] === $input) {
            return [new Error($path, 'constraint.notblank')];
        }

        return [];
    }
}
