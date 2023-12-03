<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

final class Error implements ErrorInterface
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(private string $path, private string $key, private array $arguments = []) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array<string, mixed>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
