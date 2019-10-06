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

    /**
     * @param string $wishedType
     */
    public function __construct(string $wishedType)
    {
        $this->wishedType = $wishedType;
    }

    /**
     * @param string                    $path
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
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

    /**
     * @param string $path
     * @param string $type
     *
     * @return Error
     */
    private function getInvalidTypeErrorByPathAndType(string $path, string $type): Error
    {
        return new Error(
            $path,
            'constraint.type.invalidtype',
            ['type' => $type, 'wishedType' => $this->wishedType]
        );
    }
}
