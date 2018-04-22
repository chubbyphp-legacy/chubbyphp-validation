<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Validator;

interface ValidatorContextInterface
{
    /**
     * @return string[]
     */
    public function getGroups(): array;
}
