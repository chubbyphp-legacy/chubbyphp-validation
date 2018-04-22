<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Validator;

use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;

interface FieldValidatorInterface
{
    /**
     * @param string                    $path
     * @param object                    $object
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @throws ValidatorLogicException
     */
    public function validateField(
        string $path,
        $object,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    );
}
