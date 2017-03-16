<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class ModelReferenceNullRuleException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be null',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not be null',
        ],
    ];
}
