<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Accessor;

use Chubbyphp\Validation\ValidatorLogicException;

interface AccessorInterface
{
    /**
     * @return mixed
     *
     * @throws ValidatorLogicException
     */
    public function getValue(object $object);
}
