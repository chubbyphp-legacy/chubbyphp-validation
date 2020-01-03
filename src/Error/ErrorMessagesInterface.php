<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

interface ErrorMessagesInterface
{
    /**
     * @return array<mixed>
     */
    public function getMessages(): array;
}
