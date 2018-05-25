<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

final class ValidatorContext implements ValidatorContextInterface
{
    /**
     * @var string[]
     */
    private $groups = [];

    /**
     * @param string[] $groups
     */
    public function __construct(array $groups = [])
    {
        $this->groups = $groups;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
