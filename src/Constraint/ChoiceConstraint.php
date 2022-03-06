<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class ChoiceConstraint implements ConstraintInterface
{
    /**
     * @param array<int, int|string> $choices
     */
    public function __construct(private array $choices)
    {
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
        if (null === $value) {
            return [];
        }

        if (!\in_array($value, $this->choices, true)) {
            return [
                new Error(
                    $path,
                    'constraint.choice.invalidvalue',
                    ['value' => $value, 'choices' => implode(', ', $this->choices)]
                ),
            ];
        }

        return [];
    }
}
