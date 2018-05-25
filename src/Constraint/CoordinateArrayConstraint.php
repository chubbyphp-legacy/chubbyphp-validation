<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class CoordinateArrayConstraint implements ConstraintInterface
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

        if (!is_array($value)) {
            return [new Error(
                $path,
                'constraint.coordinatearray.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        if (!isset($value['lat']) || !isset($value['lon'])) {
            return [new Error($path, 'constraint.coordinatearray.invalidformat', ['value' => json_encode($value)])];
        }

        $lat = $value['lat'];
        $lon = $value['lon'];

        if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
            return [new Error($path, 'constraint.coordinatearray.invalidvalue', ['value' => json_encode($value)])];
        }

        return [];
    }
}
