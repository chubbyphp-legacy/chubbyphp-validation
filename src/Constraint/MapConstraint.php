<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Chubbyphp\Validation\ValidatorLogicException;

final class MapConstraint implements ConstraintInterface
{
    /**
     * @var array<string, array<ConstraintInterface>>
     */
    private array $constraintsByFields;

    /**
     * @param array<string, ConstraintInterface|array<ConstraintInterface>> $constraintsByFields
     */
    public function __construct(array $constraintsByFields = [])
    {
        $this->constraintsByFields = [];
        foreach ($constraintsByFields as $field => $constraintsByField) {
            if ($constraintsByField instanceof ConstraintInterface) {
                @trigger_error(
                    sprintf(
                        'Constraints by field "%s" should be an array of "%s"',
                        $field,
                        ConstraintInterface::class
                    ),
                    E_USER_DEPRECATED
                );

                $constraintsByField = [$constraintsByField];
            }

            $this->addConstraintsByField($field, $constraintsByField);
        }
    }

    /**
     * @param mixed $value
     *
     * @throws ValidatorLogicException
     *
     * @return array<ErrorInterface>
     */
    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ?ValidatorInterface $validator = null
    ) {
        if (null === $value) {
            return [];
        }

        if (!is_array($value) && !$value instanceof \Traversable) {
            return [new Error(
                $path,
                'constraint.map.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        $errors = [];
        foreach ($value as $field => $subValue) {
            $subPath = $path.'.'.$field;

            if (!isset($this->constraintsByFields[$field])) {
                $errors[] = new Error(
                    $subPath,
                    'constraint.map.field.notallowed',
                    ['field' => $field, 'allowedFields' => array_keys($this->constraintsByFields)]
                );

                continue;
            }

            foreach ($this->constraintsByFields[$field] as $constraint) {
                $errors = array_merge(
                    $errors,
                    $constraint->validate($subPath, $subValue, $context, $validator)
                );
            }
        }

        return $errors;
    }

    /**
     * @param array<ConstraintInterface> $constraintsByField
     */
    private function addConstraintsByField(string $field, array $constraintsByField): void
    {
        $this->constraintsByFields[$field] = [];
        foreach ($constraintsByField as $constraintByField) {
            $this->addConstraintByField($field, $constraintByField);
        }
    }

    private function addConstraintByField(string $field, ConstraintInterface $constraint): void
    {
        $this->constraintsByFields[$field][] = $constraint;
    }
}
