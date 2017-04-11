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
    private $args;

    /**
     * @param string $path
     * @param string $key
     * @param array $args
     */
    public function __construct($path, $key, array $args = [])
    {
        $this->path = $path;
        $this->key = $key;
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}
