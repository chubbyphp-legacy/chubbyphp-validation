<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface RequirementInterface
{
    /**
     * @return string
     */
    public function provides(): string;

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isResponsible($value): bool;

    /**
     * @return mixed
     */
    public function getRequirement();
}
