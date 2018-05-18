<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Accessor;

use Chubbyphp\Validation\ValidatorLogicException;

interface AccessorInterface
{
    /**
     * @param object $object
     *
     * @return mixed
     *
     * @throws ValidatorLogicException
     */
    public function getValue($object);
}
