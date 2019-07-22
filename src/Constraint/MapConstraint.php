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
     * @var ConstraintInterface[]
     */
    private $constraints;

    /**
     * @param ConstraintInterface[] $constraints
     */
    public function __construct(array $constraints = [])
    {
        $this->constraints = [];
        foreach ($constraints as $name => $constraint) {
            $this->addConstraint($name, $constraint);
        }
    }

    /**
     * @param string $name
     * @param ConstraintInterface $constraint
     */
    private function addConstraint(string $name, ConstraintInterface $constraint)
    {
        $this->constraints[$name] = $constraint;
    }

    /**
     * @param string                    $path
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @return ErrorInterface[]
     *
     * @throws ValidatorLogicException
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

        if (!is_array($value) && !$value instanceof \Traversable) {
            return [new Error(
                $path.'[_all]',
                'constraint.map.invalidtype',
                ['type' => is_object($value) ? get_class($value) : gettype($value)]
            )];
        }

        $errors = [];
        foreach ($value as $field => $subValue) {
            $subPath = $path.'['.$field.']';

            if (!isset($this->constraints[$field])) {
                $errors[] = new Error(
                    $subPath,
                    'constraint.map.field.notallowed',
                    ['field' => $field, 'allowedFields' => array_keys($this->constraints)]
                );

                continue;
            }

            $constraint = $this->constraints[$field];

            $errors = array_merge(
                $errors,
                $constraint->validate($subPath, $subValue, $context, $validator)
            );
        }

        return $errors;
    }
}
