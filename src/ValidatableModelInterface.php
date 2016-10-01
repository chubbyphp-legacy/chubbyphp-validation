<?php

namespace Chubbyphp\Validation;

use Chubbyphp\Model\ModelInterface;
use Respect\Validation\Validator as RespectValidator;

interface ValidatableModelInterface extends ModelInterface
{
    /**
     * @return RespectValidator|null
     */
    public function getModelValidator();

    /**
     * @return RespectValidator[]|array
     */
    public function getPropertyValidators(): array;
}
