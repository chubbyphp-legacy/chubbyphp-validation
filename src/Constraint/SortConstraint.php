<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;

final class SortConstraint implements ConstraintInterface
{
    private const ALLOWED_ORDERS = ['asc', 'desc'];

    /**
     * @var array<string>
     */
    private array $allowedFields;

    /**
     * @param array<string> $allowedFields
     */
    public function __construct(array $allowedFields)
    {
        $this->allowedFields = $allowedFields;
    }

    /**
     * @param mixed $sort
     *
     * @throws ValidatorLogicException
     *
     * @return array<ErrorInterface>
     */
    public function validate(
        string $path,
        $sort,
        ValidatorContextInterface $context,
        ?ValidatorInterface $validator = null
    ): array {
        if (!is_array($sort)) {
            return [new Error(
                $path,
                'constraint.sort.invalidtype',
                ['type' => is_object($sort) ? get_class($sort) : gettype($sort)]
            )];
        }

        $errors = [];

        foreach ($sort as $field => $order) {
            $errors = [...$errors, ...$this->validateFieldAndOrder($path, $field, $order)];
        }

        return $errors;
    }

    /**
     * @param mixed $order
     *
     * @return array<int, Error>
     */
    private function validateFieldAndOrder(string $path, string $field, $order): array
    {
        $errors = [];

        if (!in_array($field, $this->allowedFields, true)) {
            $errors[] = new Error(
                $path.'.'.$field,
                'constraint.sort.field.notallowed',
                ['field' => $field, 'allowedFields' => $this->allowedFields]
            );
        }

        if (!is_string($order)) {
            $errors[] = new Error(
                $path.'.'.$field,
                'constraint.sort.order.invalidtype',
                ['field' => $field, 'type' => is_object($order) ? get_class($order) : gettype($order)]
            );

            return $errors;
        }

        if (!in_array($order, self::ALLOWED_ORDERS, true)) {
            $errors[] = new Error(
                $path.'.'.$field,
                'constraint.sort.order.notallowed',
                ['field' => $field, 'order' => $order, 'allowedOrders' => self::ALLOWED_ORDERS]
            );
        }

        return $errors;
    }
}
