<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

interface ValidationGroupsInterface
{
    /**
     * @return array<int, string>
     */
    public function getGroups(): array;
}
