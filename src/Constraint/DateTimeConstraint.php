<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class DateTimeConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    private $format;

    /**
     * @param string $format
     */
    public function __construct(string $format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    /**
     * @param string                    $path
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @return ErrorInterface[]
     */
    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        if (null === $value || '' === $value) {
            return [];
        }

        if ($value instanceof \DateTimeInterface) {
            return $this->validateDateTime($path, $value);
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            return [new Error(
                $path,
                'constraint.date.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        $value = trim((string) $value);

        \DateTime::createFromFormat($this->format, $value);

        $dateTimeErrors = \DateTime::getLastErrors();

        return array_merge(
            $this->errorsByDateTimeLastErrors(
                $path,
                $value,
                'error',
                $dateTimeErrors['error_count'],
                $dateTimeErrors['errors']
            ),
            $this->errorsByDateTimeLastErrors(
                $path,
                $value,
                'warning',
                $dateTimeErrors['warning_count'],
                $dateTimeErrors['warnings']
            )
        );
    }

    /**
     * @param string             $path
     * @param \DateTimeInterface $value
     *
     * @return ErrorInterface[]
     */
    private function validateDateTime(string $path, \DateTimeInterface $value): array
    {
        $expectedValue = \DateTime::createFromFormat($this->format, $value->format($this->format), $value->getTimezone());
        if ($expectedValue != $value) {
            return [
                new Error(
                    $path,
                    'constraint.date.format',
                    ['value' => $value->format('c'), 'expectedValue' => $expectedValue->format('c')]
                ),
            ];
        }

        return [];
    }

    /**
     * @param string $path
     * @param string $value
     * @param string $errorType
     * @param int    $dateTimeErrorCount
     * @param array  $dateTimeErrors
     *
     * @return ErrorInterface[]
     */
    private function errorsByDateTimeLastErrors(
        string $path,
        string $value,
        string $errorType,
        int $dateTimeErrorCount,
        array $dateTimeErrors
    ): array {
        if (0 === $dateTimeErrorCount) {
            return [];
        }

        $messages = [];
        foreach ($dateTimeErrors as $position => $message) {
            if (!isset($messages[$message])) {
                $messages[$message] = [];
            }

            $messages[$message][] = $position;
        }

        $errors = [];
        foreach ($messages as $message => $positions) {
            $errors[] = new Error(
                $path,
                sprintf('constraint.date.%s', $errorType),
                ['message' => $message, 'positions' => $positions, 'value' => $value]
            );
        }

        return $errors;
    }
}
