<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Validator;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class FieldValidator implements FieldValidatorInterface
{
    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param AccessorInterface $accessor
     */
    public function __construct(AccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    public function validateField(
        string $path,
        $object,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        // TODO: Implement validateField() method.
    }

}
