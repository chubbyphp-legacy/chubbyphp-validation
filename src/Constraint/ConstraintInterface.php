<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

interface ConstraintInterface
{
    /**
     * @param string $path
     * @param mixed $input
     * @param ValidatorInterface|null $validator
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input, ValidatorInterface $validator = null): array;
}
