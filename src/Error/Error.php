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
     * @var mixed
     */
    private $input;

    /**
     * @var array
     */
    private $args;

    /**
     * @param string $path
     * @param string $key
     * @param mixed $input
     * @param array $args
     */
    public function __construct($path, $key, $input, array $args = [])
    {
        $this->path = $path;
        $this->key = $key;
        $this->input = $input;
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
     * @return mixed
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }
}
