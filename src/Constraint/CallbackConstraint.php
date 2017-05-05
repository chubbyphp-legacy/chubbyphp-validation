<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\ValidatorInterface;

final class CallbackConstraint implements ConstraintInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param string                  $path
     * @param mixed                   $input
     * @param ValidatorInterface|null $validator
     *
     * @return array
     */
    public function validate(string $path, $input, ValidatorInterface $validator = null): array
    {
        $callback = $this->callback;

        return $callback($path, $input, $validator);
    }
}
