<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class CoordinateConstraint implements ConstraintInterface
{
    const PATTERN = '/^([-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)),\s*([-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?))$/';

    /**
     * @param string                  $path
     * @param mixed                   $input
     * @param ValidatorInterface|null $validator
     *
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input, ValidatorInterface $validator = null): array
    {
        if (null === $input) {
            return [];
        }

        if (!is_string($input)) {
            return [new Error($path, 'constraint.coordinate.invalidtype', ['type' => gettype($input)])];
        }

        if (!preg_match(self::PATTERN, $input)) {
            return [new Error($path, 'constraint.coordinate.invalidformat', ['input' => $input])];
        }

        return [];
    }
}
