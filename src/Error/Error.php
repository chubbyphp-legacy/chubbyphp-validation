<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

final class Error implements ErrorInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @param string $path
     * @param string $key
     */
    public function __construct($path, $key, array $arguments = [])
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

    public function getArguments(): array
    {
        return $this->arguments;
    }
}
