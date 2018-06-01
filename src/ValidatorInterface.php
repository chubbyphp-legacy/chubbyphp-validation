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
     * @return ErrorInterface[]
     */
    public function validate($object, ValidatorContextInterface $context = null, string $path = '');
}
