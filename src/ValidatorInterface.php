<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Validator\ValidatorContextInterface;

interface ValidatorInterface
{
    /**
     * @param object $object
     * @param ValidatorContextInterface $context
     * @param string                       $path
     *
     * @return object
     */
    public function validate(
        $object,
        ValidatorContextInterface $context = null,
        string $path = ''
    );
}
