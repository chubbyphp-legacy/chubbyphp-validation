<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

final class ValidatorContext implements ValidatorContextInterface
{
    /**
     * @var array<int, string>
     */
    private array $groups = [];

    /**
     * @param array<int, string> $groups
     */
    public function __construct(array $groups = [])
    {
        $this->groups = $groups;
    }

    /**
     * @return array<int, string>
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
