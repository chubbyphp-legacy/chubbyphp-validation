<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\ErrorInterface;

interface ConstraintInterface
{
    /**
     * @param string $path
     * @param mixed $input
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input): array;
}
