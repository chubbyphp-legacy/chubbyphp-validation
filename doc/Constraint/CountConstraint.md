# CountConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CountConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new CountConstraint(1, 2);

/** @var ValidatorContextInterface $context */
$context = ...;

// Use NotNullConstraint to prevent null
$errors = $constraint->validate(
    'path.to.property',
    null,
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    '',
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.count.invalidtype',
//         ['type' => 'string']
//     )
// ];

$errors = $constraint->validate(
    'path.to.property',
    [],
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.count.outofrange',
//         ['count' => 0, 'min' => 1, 'max' => 2]
//     )
// ];

$errors = $constraint->validate(
    'path.to.property',
    ['value'],
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    ['value', 'value'],
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    ['value', 'value', 'value'],
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.count.outofrange',
//         ['count' => 3, 'min' => 1, 'max' => 2]
//     )
// ];
```
