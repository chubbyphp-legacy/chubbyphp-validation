<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

final class ErrorMessages implements ErrorMessagesInterface
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
     * @var string[]
     */
    private $errorMessages;

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
     * @return string[]
     */
    public function getMessages(): array
    {
        if (null === $this->errorMessages) {
            $translate = $this->translate;
            $this->errorMessages = [];
            foreach ($this->errors as $error) {
                $path = $error->getPath();
                if (!isset($this->errorMessages[$path])) {
                    $this->errorMessages[$path] = [];
                }
                $this->errorMessages[$path][] = $translate($error->getKey(), $error->getArguments());
            }
        }

        return $this->errorMessages;
    }
}
