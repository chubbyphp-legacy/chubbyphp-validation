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
        if (null === $value) {
            return [];
        }

        if ($value instanceof \DateTimeInterface) {
            return [];
        }

        if (!is_string($value)) {
            return [new Error(
                $path,
                'constraint.date.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        \DateTime::createFromFormat($this->format, $value);

        $dateTimeErrors = \DateTime::getLastErrors();

        if (0 === $dateTimeErrors['warning_count'] && 0 === $dateTimeErrors['error_count']) {
            return [];
        }

        $errors = [];
        foreach ($dateTimeErrors['warnings'] as $code => $message) {
            $errors[] = new Error(
                $path,
                'constraint.date.warning',
                ['code' => $code, 'message' => $message, 'format' => $this->format, 'value' => $value]
            );
        }

        foreach ($dateTimeErrors['errors'] as $code => $message) {
            $errors[] = new Error(
                $path,
                'constraint.date.error',
                ['code' => $code, 'message' => $message, 'format' => $this->format, 'value' => $value]
            );
        }

        return $errors;
    }
}
