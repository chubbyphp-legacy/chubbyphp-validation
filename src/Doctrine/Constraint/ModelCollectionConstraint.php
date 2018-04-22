<?php

declare(strict_types=1);

namespace Chubbyphp\ValidationDoctrine\Constraint;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Doctrine\Common\Collections\Collection;

final class ModelCollectionConstraint implements ConstraintInterface
{
    /**
     * @var bool
     */
    private $recursive;

    /**
     * @var int|null
     */
    private $min;

    /**
     * @var int|null
     */
    private $max;

    /**
     * @param bool     $recursive
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct(bool $recursive = true, int $min = null, int $max = null)
    {
        $this->recursive = $recursive;
        $this->min = $min;
        $this->max = $max;
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
        $this->validInputTypeOrException($input);

        $errors = [];

        $count = count($input);

        if (null !== $this->min && $count < $this->min || null !== $this->max && $count > $this->max) {
            $errors[] = new Error(
                 $path.'[_all]',
                'constraint.modelcollection.outofrange',
                ['count' => $count, 'min' => $this->min, 'max' => $this->max]
            );
        }

        if ($this->recursive) {
            if (null === $validator) {
                throw new \RuntimeException('Recursive validation is only possible if validator given');
            }

            foreach ($input->toArray() as $i => $model) {
                $errors = array_merge($errors, $validator->validateObject($model, $path.'['.$i.']'));
            }
        }

        return $errors;
    }

    /**
     * @param Collection $input
     */
    private function validInputTypeOrException($input)
    {
        if (!$input instanceof Collection) {
            throw new \RuntimeException(
                sprintf('Invalid input, instance of %s needed', Collection::class)
            );
        }
    }
}
