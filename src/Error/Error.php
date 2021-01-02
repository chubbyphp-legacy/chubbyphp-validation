<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

final class Error implements ErrorInterface
{
    private string $path;

    private string $key;

    /**
     * @var array<string, mixed>
     */
    private array $arguments;

    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(string $path, string $key, array $arguments = [])
    {
        $this->path = $path;
        $this->key = $key;
        $this->arguments = $arguments;
    }

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
