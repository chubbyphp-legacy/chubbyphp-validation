<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class UniqueModelRuleException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} are not unique',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} are unique',
        ],
    ];
}
