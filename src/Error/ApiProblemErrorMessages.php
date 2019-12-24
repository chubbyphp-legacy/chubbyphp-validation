<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Error;

final class ApiProblemErrorMessages implements ErrorMessagesInterface
{
    /**
     * @var array<ErrorInterface>
     */
    private $errors;

    /**
     * @var array<int, array<string, string|array>>
     */
    private $errorMessages;

    /**
     * @param array<ErrorInterface> $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = [];
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * @return array<int, array<string, string|array>>
     */
    public function getMessages(): array
    {
        if (null === $this->errorMessages) {
            $this->errorMessages = [];
            foreach ($this->errors as $error) {
                $this->errorMessages[] = [
                    'name' => $error->getPath(),
                    'reason' => $error->getKey(),
                    'details' => $error->getArguments(),
                ];
            }
        }

        return $this->errorMessages;
    }

    private function addError(ErrorInterface $error): void
    {
        $this->errors[] = $error;
    }
}
