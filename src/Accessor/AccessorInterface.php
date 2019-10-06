<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Accessor;

use Chubbyphp\Validation\ValidatorLogicException;

interface AccessorInterface
{
    /**
     * @param object $object
     *
     * @throws ValidatorLogicException
     *
     * @return mixed
     */
    public function getValue($object);
}
