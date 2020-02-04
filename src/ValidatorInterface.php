<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Error\ErrorInterface;

interface ValidatorInterface
{
    /**
     * @return array<ErrorInterface>
     */
    public function validate(object $object, ?ValidatorContextInterface $context = null, string $path = '');
}
