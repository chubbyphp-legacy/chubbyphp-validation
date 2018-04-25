<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;

interface ConstraintInterface
{
    /**
     * @param string                    $path
     * @param object                    $object,
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @return ErrorInterface[]
     *
     * @throws ValidatorLogicException
     */
    public function validate(
        string $path,
        $object,
        $value,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    );
}
