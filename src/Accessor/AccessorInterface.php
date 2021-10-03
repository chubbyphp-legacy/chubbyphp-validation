<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Accessor;

use Chubbyphp\Validation\ValidatorLogicException;

interface AccessorInterface
{
    /**
     * @throws ValidatorLogicException
     *
     * @return mixed
     */
    public function getValue(object $object);
}
