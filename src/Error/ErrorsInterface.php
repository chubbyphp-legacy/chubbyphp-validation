<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

interface ErrorsInterface
{
    /**
     * @return ErrorInterface[]
     */
    public function getErrors(): array;

    /**
     * @return array
     */
    public function getTree(): array;
}
