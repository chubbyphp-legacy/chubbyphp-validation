<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

final class ValidatorContextBuilder implements ValidatorContextBuilderInterface
{
    /**
     * @var array<int, string>
     */
    private ?array $groups = [];

    private function __construct()
    {
    }

    public static function create(): ValidatorContextBuilderInterface
    {
        return new self();
    }

    /**
     * @param array<int, string> $groups
     */
    public function setGroups(array $groups): ValidatorContextBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    public function getContext(): ValidatorContextInterface
    {
        return new ValidatorContext($this->groups);
    }
}
