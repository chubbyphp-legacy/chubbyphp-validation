<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;

final class ChoiceConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    private $type;

    const TYPE_BOOL = 'boolean';
    const TYPE_FLOAT = 'double';
    const TYPE_INT = 'integer';
    const TYPE_STRING = 'string';

    /**
     * @var array
     */
    private $choices = [];

    /**
     * @var bool
     */
    private $allowStringCompare;

    /**
     * @param string $type
     * @param array  $choices
     * @param bool   $allowStringCompare
     */
    public function __construct(string $type, array $choices, bool $allowStringCompare = false)
    {
        $this->type = $type;
        foreach ($choices as $i => $choice) {
            $choiceType = is_object($choice) ? get_class($choice) : gettype($choice);
            if ($choiceType !== $this->type) {
                throw new \InvalidArgumentException(
                    sprintf('Choice %s got type "%s", but type "%s" required', $i, $choiceType, $this->type)
                );
            }
            $this->choices[] = $choice;
        }
        $this->allowStringCompare = $allowStringCompare;
    }

    /**
     * @param string                  $path
     * @param mixed                   $input
     * @param ValidatorInterface|null $validator
     *
     * @return ErrorInterface[]
     */
    public function validate(string $path, $input, ValidatorInterface $validator = null): array
    {
        if (null === $input) {
            return [];
        }

        $inputType = is_object($input) ? get_class($input) : gettype($input);

        if ($this->type !== $inputType) {
            if ($this->allowStringCompare && self::TYPE_STRING === $inputType
                && in_array($this->type, [self::TYPE_BOOL, self::TYPE_FLOAT, self::TYPE_INT], true)
            ) {
                $stringChoices = $this->getStringChoices($this->choices);
                if (!in_array($input, $stringChoices, true)) {
                    return [
                        new Error(
                            $path,
                            'constraint.choice.invalidvalue',
                            ['input' => $input, 'choices' => $stringChoices]
                        ),
                    ];
                }
            } else {
                return [new Error($path, 'constraint.choice.invalidtype', ['type' => $inputType])];
            }
        } else {
            if (!in_array($input, $this->choices, true)) {
                return [
                    new Error(
                        $path,
                        'constraint.choice.invalidvalue',
                        ['input' => $input, 'choices' => $this->choices]
                    ),
                ];
            }
        }

        return [];
    }

    /**
     * @param array $choices
     *
     * @return array
     */
    private function getStringChoices(array $choices): array
    {
        $stringChoices = [];
        foreach ($choices as $choice) {
            if (self::TYPE_FLOAT === $this->type && (string) (int) $choice === (string) $choice) {
                $stringChoices[] = (string) $choice.'.0';
            } else {
                $stringChoices[] = (string) $choice;
            }
        }

        return $stringChoices;
    }
}
