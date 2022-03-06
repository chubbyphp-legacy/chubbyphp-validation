<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

final class ValidatorContext implements ValidatorContextInterface
{
    /**
     * @param array<int, string> $groups
     */
    public function __construct(private array $groups = [])
    {
    }

    /**
     * @return array<int, string>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
