<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

use Chubbyphp\Model\ModelInterface;
use Respect\Validation\Validator as v;

interface ValidatableModelInterface extends ModelInterface
{
    /**
     * @return v|null
     */
    public function getModelValidator();

    /**
     * @return v[]|array
     */
    public function getPropertyValidators(): array;
}
