<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

final class NestedErrorMessages implements ErrorMessagesInterface
{
    /**
     * @var array<int, ErrorInterface>
     */
    private array $errors;

    /**
     * @var callable
     */
    private $translate;

    /**
     * @var array<string, array>|null
     */
    private ?array $nestedErrorMessages = null;

    /**
     * @param array<ErrorInterface> $errors
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
     * @return array<mixed>
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

    private function addError(ErrorInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param array<string, array> $node
     */
    private function assignErrorMessage(array &$node, ErrorInterface $error): void
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
     * @return array<int|string>
     */
    private function parsePath(string $path): array
    {
        $pathParts = [];
        foreach (explode('.', $path) as $pathPart) {
            $matches = [];
            if (1 === preg_match('/^(([^\[]+)\[(\d+|_all)\])$/', $pathPart, $matches)) {
                $pathParts[] = $matches[2];
                $pathParts[] = '_all' !== $matches[3] ? (int) $matches[3] : $matches[3];
            } else {
                $pathParts[] = $pathPart;
            }
        }

        return $pathParts;
    }
}
