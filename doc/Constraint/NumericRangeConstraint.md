# NumericRangeConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NumericRangeConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

$constraint = new NumericRangeConstraint(1, 2);

/** @var ValidatorContextInterface $context */
$context = ...;

// Use NotNullConstraint to prevent null
$errors = $constraint->validate(
    'path.to.property',
    null,
    $context
);
// [];

// Use NotBlankConstraint to prevent ''
$errors = $constraint->validate(
    'path.to.property',
    '',
    $context
);
// [];

// Use NumericConstraint to prevent not numeric
$errors = $constraint->validate(
    'path.to.property',
    'test',
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    0,
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.numericrange.outofrange',
//         ['value' => 0, 'min' => 1, 'max' => 2]
//     )
// ];

$errors = $constraint->validate(
    'path.to.property',
    1,
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    2,
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    3,
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.numericrange.outofrange',
//         ['value' => 3, 'min' => 1, 'max' => 2]
//     )
// ];
```
