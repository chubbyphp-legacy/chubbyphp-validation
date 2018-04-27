<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class CoordinateConstraint implements ConstraintInterface
{
    const PATTERN = '/^([-+]?\d+(\.\d+)?)\s*,\s*([-+]?\d+(\.\d+)?)$/';

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

        if (!is_string($value)) {
            return [new Error($path, 'constraint.coordinate.invalidtype', ['type' => gettype($value)])];
        }

        $matches = [];

        if (!preg_match(self::PATTERN, $value, $matches)) {
            return [new Error($path, 'constraint.coordinate.invalidformat', ['value' => $value])];
        }

        $lat = (float) $matches[1];
        $lon = (float) $matches[3];

        if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
            return [new Error($path, 'constraint.coordinate.invalidvalue', ['value' => $value])];
        }

        return [];
    }
}
