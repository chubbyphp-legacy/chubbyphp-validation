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
     * @param mixed $value
     *
     * @return array<ErrorInterface>
     */
    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ?ValidatorInterface $validator = null
    ) {
        if (null === $value) {
            return [];
        }

        if (!\is_array($value)) {
            return [new Error(
                $path,
                'constraint.coordinatearray.invalidtype',
                ['type' => get_debug_type($value)]
            )];
        }

        if (!isset($value['lat']) || !isset($value['lon'])) {
            return [new Error($path, 'constraint.coordinatearray.invalidformat', ['value' => json_encode($value, JSON_THROW_ON_ERROR)])];
        }

        $lat = $value['lat'];
        $lon = $value['lon'];

        if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
            return [new Error($path, 'constraint.coordinatearray.invalidvalue', ['value' => json_encode($value, JSON_THROW_ON_ERROR)])];
        }

        return [];
    }
}
