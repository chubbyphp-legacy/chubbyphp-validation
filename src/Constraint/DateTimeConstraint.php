<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class DateTimeConstraint implements ConstraintInterface
{
    private string $format;

    public function __construct(string $format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    /**
     * @param mixed $value
     *
     * @return array<ErrorInterface>
     */
    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ?ValidatorInterface $validator = null
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
                'constraint.datetime.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        $value = trim((string) $value);

        \DateTime::createFromFormat('!'.$this->format, $value);

        $dateTimeErrors = \DateTime::getLastErrors();

        return [...$this->errorsByDateTimeLastErrors(
            $path,
            $value,
            'error',
            $dateTimeErrors['error_count'],
            $dateTimeErrors['errors']
        ), ...$this->errorsByDateTimeLastErrors(
            $path,
            $value,
            'warning',
            $dateTimeErrors['warning_count'],
            $dateTimeErrors['warnings']
        )];
    }

    /**
     * @return array<ErrorInterface>
     */
    private function validateDateTime(string $path, \DateTimeInterface $value): array
    {
        /** @var \DateTime $expectedValue */
        $expectedValue = \DateTime::createFromFormat(
            '!'.$this->format,
            $value->format($this->format),
            $value->getTimezone()
        );

        if ($expectedValue->format('c') !== $value->format('c')) {
            return [
                new Error(
                    $path,
                    'constraint.datetime.format',
                    ['format' => $this->format, 'value' => $value->format('c')]
                ),
            ];
        }

        return [];
    }

    /**
     * @param array<int, string> $dateTimeErrors
     *
     * @return array<int, ErrorInterface>
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

        $errors = [];
        foreach ($dateTimeErrors as $message) {
            $errors[] = new Error(
                $path,
                sprintf('constraint.datetime.%s', $errorType),
                ['message' => $message, 'format' => $this->format, 'value' => $value]
            );
        }

        return $errors;
    }
}
