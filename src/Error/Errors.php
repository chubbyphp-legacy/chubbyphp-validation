<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

final class Errors implements ErrorsInterface
{
    /**
     * @var ErrorInterface[]
     */
    private $errors;

    /**
     * @var array
     */
    private $tree;

    /**
     * @param ErrorInterface[] $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = [];
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * @param ErrorInterface $error
     */
    private function addError(ErrorInterface $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return ErrorInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getTree(): array
    {
        if (null === $this->tree) {
            $this->tree = [];
            foreach ($this->errors as $error) {
                $this->assignError($this->tree, $error);
            }
        }

        return $this->tree;
    }

    /**
     * @param string $path
     * @return array
     */
    private function parsePath(string $path): array
    {
        $pathParts = [];
        foreach (explode('.', $path) as $pathPart) {
            $matches = [];
            if (1 === preg_match('/^(([^\[]+)\[(\d+)\])$/', $pathPart, $matches)) {
                $pathParts[] = $matches[2];
                $pathParts[] = (int) $matches[3];
            } else {
                $pathParts[] = $pathPart;
            }
        }

        return $pathParts;
    }

    /**
     * @param array $node
     * @param ErrorInterface $error
     */
    private function assignError(array &$node, ErrorInterface $error)
    {
        $pathParts = $this->parsePath($error->getPath());
        foreach ($pathParts as $pathPart) {
            if (!isset($node[$pathPart])) {
                $node[$pathPart] = [];
            }
            $node = &$node[$pathPart];
        }

        $node[] = $error;
    }
}
