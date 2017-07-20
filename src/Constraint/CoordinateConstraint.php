<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class CoordinateConstraint implements ConstraintInterface
{
    const PATTERN = '/^([-+]?\d+(\.\d+)?)\s*,\s*([-+]?\d+(\.\d+)?)$/';

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

        $matches = [];

        if (!preg_match(self::PATTERN, $input, $matches)) {
            return [new Error($path, 'constraint.coordinate.invalidformat', ['input' => $input])];
        }

        $lat = $matches[1];
        $lon = $matches[3];

        if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
            return [new Error($path, 'constraint.coordinate.invalidvalue', ['input' => $input])];
        }

        return [];
    }
}
