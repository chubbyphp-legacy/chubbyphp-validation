<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class CoordinateArrayConstraint implements ConstraintInterface
{
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

        if (!is_array($input)) {
            return [new Error($path, 'constraint.coordinatearray.invalidtype', ['type' => gettype($input)])];
        }

        if (!isset($input['lat']) || !isset($input['lon'])) {
            return [new Error($path, 'constraint.coordinatearray.invalidformat', ['input' => json_encode($input)])];
        }

        $lat = $input['lat'];
        $lon = $input['lon'];

        if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
            return [new Error($path, 'constraint.coordinatearray.invalidvalue', ['input' => json_encode($input)])];
        }

        return [];
    }
}
