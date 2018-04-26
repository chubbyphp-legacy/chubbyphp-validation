<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint\Symfony;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;

final class ConstraintAdapter implements ConstraintInterface
{
    /**
     * @var Constraint
     */
    private $symfonyConstraint;

    /**
     * @var ConstraintValidator
     */
    private $symfonyValidator;

    /**
     * @param Constraint                   $symfonyConstraint
     * @param ConstraintValidatorInterface $symfonyValidator
     */
    public function __construct(Constraint $symfonyConstraint, ConstraintValidatorInterface $symfonyValidator)
    {
        $this->symfonyConstraint = $symfonyConstraint;
        $this->symfonyValidator = $symfonyValidator;
    }

    /**
     * @param string                    $path
     * @param object                    $object,
     * @param mixed                     $value
     * @param ValidatorContextInterface $context
     * @param ValidatorInterface|null   $validator
     *
     * @return ErrorInterface[]
     */
    public function validate(
        string $path,
        $object,
        $value,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        $executionContext = new ExecutionContext($this->symfonyConstraint, $path, $object, $value);

        $this->symfonyValidator->initialize($executionContext);
        $this->symfonyValidator->validate($value, $this->symfonyConstraint);

        return $executionContext->getErrors();
    }
}
