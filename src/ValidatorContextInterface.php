<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface ValidatorContextInterface
{
    /**
     * @return string[]
     */
    public function getGroups(): array;
}
