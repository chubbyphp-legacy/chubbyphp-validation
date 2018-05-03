<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

interface ValidatorInterface
{
    /**
     * @param object                    $object
     * @param ValidatorContextInterface $context
     * @param string                    $path
     *
     * @return ErrorInterface[]
     */
    public function validate($object, ValidatorContextInterface $context = null, string $path = '');

    /**
     * @param mixed                 $value
     * @param ConstraintInterface[] $constraints
     * @param ValidatorContextInterface $context
     *
     * @return @return ErrorInterface[]
     */
    public function validateByConstraints($value, array $constraints, ValidatorContextInterface $context = null);
}
