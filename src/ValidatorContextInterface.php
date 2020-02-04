<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface ValidatorContextInterface
{
    /**
     * @return array<int, string>
     */
    public function getGroups(): array;
}
