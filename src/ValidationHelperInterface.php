<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Respect\Validation\Rules\AbstractRule;

interface ValidationHelperInterface
{
    /**
     * @param AbstractRule $rule
     * @param mixed        $value
     *
     * @return bool
     */
    public function isResponsible(AbstractRule $rule, $value): bool;

    /**
     * @param AbstractRule $rule
     * @param mixed        $value
     */
    public function help(AbstractRule $rule, $value);
}
