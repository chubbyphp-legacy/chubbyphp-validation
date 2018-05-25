# ConstraintAdapter (Symfony)

```php
<?php

use Chubbyphp\Validation\Constraint\Symfony\ConstraintAdapter;
use Chubbyphp\Validation\ValidatorContextInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotBlankValidator;

$constraint = new ConstraintAdapter(new NotBlank(), new NotBlankValidator());

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $constraint->validate(
    'path.to.property',
    'notBlank',
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    '',
    $context
);
// new Error('notBlank', 'This value should not be blank.', [
//     'parameters' => [
//         '{{ value }}' => '""',
//     ],
//     'plural' => null,
//     'invalidValue' => null,
//     'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
//     'cause' => null,
// ]);
```
