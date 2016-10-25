<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface LazyRequirementInterface
{
    /**
     * @return string[]|array
     */
    public function requires(): array;

    /**
     * @param string $name
     * @param $value
     *
     * @throws \InvalidArgumentException
     */
    public function setRequirement(string $name, $value);
}
