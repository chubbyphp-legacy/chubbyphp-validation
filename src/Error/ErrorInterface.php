<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

interface ErrorInterface
{
    public function getPath(): string;

    public function getKey(): string;

    public function getArguments(): array;
}
