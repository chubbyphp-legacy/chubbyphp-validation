<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

interface ErrorMessagesInterface
{
    /**
     * @return array
     */
    public function getMessages(): array;
}
