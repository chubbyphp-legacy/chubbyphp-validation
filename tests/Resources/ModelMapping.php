<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Resources;

use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Constraint\RangeConstraint;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use Chubbyphp\Validation\Mapping\PropertyMappingInterface;

final class ModelMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Model::class;
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
            new PropertyMapping('notNull', [new NotNullConstraint()]),
            new PropertyMapping('notBlank', [new NotBlankConstraint()]),
            new PropertyMapping('range', [new RangeConstraint(1, 10)])
        ];
    }
}
