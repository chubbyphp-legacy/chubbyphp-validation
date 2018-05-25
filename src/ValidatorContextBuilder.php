<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

final class ValidatorContextBuilder implements ValidatorContextBuilderInterface
{
    /**
     * @var string[]
     */
    private $groups;

    private function __construct()
    {
    }

    /**
     * @return ValidatorContextBuilderInterface
     */
    public static function create(): ValidatorContextBuilderInterface
    {
        $self = new self();
        $self->groups = [];

        return $self;
    }

    /**
     * @param string[] $groups
     *
     * @return ValidatorContextBuilderInterface
     */
    public function setGroups(array $groups): ValidatorContextBuilderInterface
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return ValidatorContextInterface
     */
    public function getContext(): ValidatorContextInterface
    {
        return new ValidatorContext($this->groups);
    }
}
