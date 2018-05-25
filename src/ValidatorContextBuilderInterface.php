<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface ValidatorContextBuilderInterface
{
    /**
     * @return self
     */
    public static function create(): self;

    /**
     * @param string[] $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self;

    /**
     * @return ValidatorContextInterface
     */
    public function getContext(): ValidatorContextInterface;
}
