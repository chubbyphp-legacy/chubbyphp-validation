<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;

interface ConstraintInterface
{
    /**
     * @param string                    $path
     * @param object                    $object
     * @param AccessorInterface         $accessor
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @throws ValidatorLogicException
     */
    public function validateField(
        string $path,
        $object,
        AccessorInterface $accessor,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    );
}
