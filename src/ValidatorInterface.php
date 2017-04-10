<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface ValidatorInterface
{
    /**
     * @param object $object
     * @return string[]
     */
    public function validateObject($object): array;
}
