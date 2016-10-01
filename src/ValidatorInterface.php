<?php

namespace Chubbyphp\Validation;

interface ValidatorInterface
{
    /**
     * @param ValidatableModelInterface $model
     *
     * @return array
     */
    public function validateModel(ValidatableModelInterface $model): array;

    /**
     * @param array $data
     * @param array $validators
     *
     * @return array
     */
    public function validateArray(array $data, array $validators): array;
}
