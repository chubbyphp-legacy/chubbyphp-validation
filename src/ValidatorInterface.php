<?php

declare(strict_types=1);

namespace Chubbyphp\Validation;

interface ValidatorInterface
{
    /**
     * @param ValidatableModelInterface $model
     * @param string                    $locale
     *
     * @return array
     */
    public function validateModel(ValidatableModelInterface $model, string $locale = 'en'): array;

    /**
     * @param array  $data
     * @param array  $validators
     * @param string $locale
     *
     * @return array
     */
    public function validateArray(array $data, array $validators, string $locale = 'en'): array;
}
