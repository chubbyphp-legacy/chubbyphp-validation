<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Constraint;

use Chubbyphp\Validation\Constraint\Symfony\ExecutionContext;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class SymfonyConstraintAdapter implements ConstraintInterface
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
     * @param Constraint          $symfonyConstraint
     * @param ConstraintValidator $symfonyValidator
     */
    public function __construct(Constraint $symfonyConstraint, ConstraintValidator $symfonyValidator)
    {
        $this->symfonyConstraint = $symfonyConstraint;
        $this->symfonyValidator = $symfonyValidator;
    }

    public function validate(
        string $path,
        $value,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        $executionContext = new ExecutionContext($path);

        $this->symfonyValidator->initialize($executionContext);
        $this->symfonyValidator->validate($value, $this->symfonyConstraint);

        return $executionContext->getErrors();
    }
}
