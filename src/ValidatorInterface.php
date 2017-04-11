<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface ValidatorInterface
{
    /**
     * @param object $object
     * @param string $path
     * @return string[]
     */
    public function validateObject($object, string $path = ''): array;
}
