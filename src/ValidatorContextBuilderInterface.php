<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface ValidatorContextBuilderInterface
{
    public static function create(): self;

    /**
     * @param string[] $groups
     */
    public function setGroups(array $groups): self;

    public function getContext(): ValidatorContextInterface;
}
