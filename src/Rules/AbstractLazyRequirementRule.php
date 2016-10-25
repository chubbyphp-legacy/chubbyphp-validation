<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Rules;

use Chubbyphp\Validation\LazyRequirementInterface;
use Respect\Validation\Rules\AbstractRule;

abstract class AbstractLazyRequirementRule extends AbstractRule implements LazyRequirementInterface
{
    /**
     * @param string $name
     * @param $value
     */
    public function setRequirement(string $name, $value)
    {
        if (!in_array($name, $this->requires(), true)) {
            throw new \InvalidArgumentException(sprintf('There is no requirement with name %s', $name));
        }

        $this->$name = $value;
    }
}
