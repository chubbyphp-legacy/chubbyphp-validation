<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class TypeConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    private $wishedType;

    public function __construct(string $wishedType)
    {
        $this->wishedType = $wishedType;
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
        ValidatorInterface $validator = null
    ) {
        if (null === $value) {
            return [];
        }

        $type = gettype($value);

        if ('object' === $type) {
            if ($value instanceof $this->wishedType) {
                return [];
            }

            return [$this->getInvalidTypeErrorByPathAndType($path, get_class($value))];
        }

        if ($type === $this->wishedType) {
            return [];
        }

        return [$this->getInvalidTypeErrorByPathAndType($path, $type)];
    }

    private function getInvalidTypeErrorByPathAndType(string $path, string $type): Error
    {
        return new Error(
            $path,
            'constraint.type.invalidtype',
            ['type' => $type, 'wishedType' => $this->wishedType]
        );
    }
}
