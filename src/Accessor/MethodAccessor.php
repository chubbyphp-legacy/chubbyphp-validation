<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Accessor;

use Chubbyphp\Validation\ValidatorLogicException;

final class MethodAccessor implements AccessorInterface
{
    private string $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    /**
     * @param object $object
     *
     * @throws ValidatorLogicException
     *
     * @return mixed
     */
    public function getValue($object)
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
            get_class($object), [$getMethodName, $hasMethodName, $isMethodName]
        );
    }
}
