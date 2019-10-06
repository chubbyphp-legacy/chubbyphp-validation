<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Validation\Error\ErrorInterface;

interface ValidatorInterface
{
    /**
     * @param object                         $object
     * @param ValidatorContextInterface|null $context
     * @param string                         $path
     *
     * @return array<ErrorInterface>
     */
    public function validate(object $object, ValidatorContextInterface $context = null, string $path = '');
}
