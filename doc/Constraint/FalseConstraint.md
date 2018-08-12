# FalseConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\FalseConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new FalseConstraint();

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
    false,
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    true,
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.false.notfalse'
//     )
// ];

$errors = $constraint->validate(
    'path.to.property',
    '',
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.false.notfalse'
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
//         'constraint.false.notfalse'
//     )
// ];

$errors = $constraint->validate(
    'path.to.property',
    new \stdClass,
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.false.notfalse'
//     )
// ];
```
