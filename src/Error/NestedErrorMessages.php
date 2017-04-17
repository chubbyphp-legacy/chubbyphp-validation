<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

final class NestedErrorMessages implements ErrorMessagesInterface
{
    /**
     * @var ErrorInterface[]
     */
    private $errors;

    /**
     * @var callable
     */
    private $translate;

    /**
     * @var array
     */
    private $nestedErrorMessages;

    /**
     * @param ErrorInterface[] $errors
     */
    public function __construct(array $errors, callable $translate)
    {
        $this->errors = [];
        foreach ($errors as $error) {
            $this->addError($error);
        }
        $this->translate = $translate;
    }

    /**
     * @param ErrorInterface $error
     */
    private function addError(ErrorInterface $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        if (null === $this->nestedErrorMessages) {
            $this->nestedErrorMessages = [];
            foreach ($this->errors as $error) {
                $this->assignErrorMessage($this->nestedErrorMessages, $error);
            }
        }

        return $this->nestedErrorMessages;
    }

    /**
     * @param array $node
     * @param ErrorInterface $error
     */
    private function assignErrorMessage(array &$node, ErrorInterface $error)
    {
        $pathParts = $this->parsePath($error->getPath());
        foreach ($pathParts as $pathPart) {
            if (!isset($node[$pathPart])) {
                $node[$pathPart] = [];
            }
            $node = &$node[$pathPart];
        }

        $translate = $this->translate;

        $node[] = $translate($error->getKey(), $error->getArguments());
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
            if (1 === preg_match('/^(([^\[]+)\[(\d+|_all)\])$/', $pathPart, $matches)) {
                $pathParts[] = $matches[2];
                $pathParts[] = $matches[3] !== '_all' ? (int) $matches[3] : $matches[3];
            } else {
                $pathParts[] = $pathPart;
            }
        }

        return $pathParts;
    }
}
