<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Mapping;

use Chubbyphp\Validation\Accessor\AccessorInterface;
use Chubbyphp\Validation\Constraint\ConstraintInterface;

interface ValidationPropertyMappingInterface extends ValidationGroupsInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array;

    /**
     * @return string|null
     */
    public function getForceType();

    const FORCETYPE_BOOL = 'boolean';
    const FORCETYPE_INT = 'integer';
    const FORCETYPE_FLOAT = 'float';
    const FORCETYPE_STRING = 'string';

    const FORCETYPES = [
        self::FORCETYPE_BOOL,
        self::FORCETYPE_INT,
        self::FORCETYPE_FLOAT,
        self::FORCETYPE_STRING,
    ];

    /**
     * @return AccessorInterface
     */
    public function getAccessor(): AccessorInterface;
}
