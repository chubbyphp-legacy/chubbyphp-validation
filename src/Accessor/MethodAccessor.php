<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Accessor;

use Chubbyphp\Validation\ValidatorLogicException;

final class MethodAccessor implements AccessorInterface
{
    public function __construct(private string $property)
    {
    }

    /**
     * @throws ValidatorLogicException
     *
     * @return mixed
     */
    public function getValue(object $object)
    {
        $getMethodName = 'get'.ucfirst($this->property);
        $hasMethodName = 'has'.ucfirst($this->property);
        $isMethodName = 'is'.ucfirst($this->property);

        if (method_exists($object, $getMethodName)) {
            return $object->{$getMethodName}();
        }

        if (method_exists($object, $hasMethodName)) {
            return $object->{$hasMethodName}();
        }

        if (method_exists($object, $isMethodName)) {
            return $object->{$isMethodName}();
        }

        throw ValidatorLogicException::createMissingMethod(
            $object::class,
            [$getMethodName, $hasMethodName, $isMethodName]
        );
    }
}
