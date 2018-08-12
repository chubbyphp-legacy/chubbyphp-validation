# TrueConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\TrueConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new TrueConstraint();

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
    true,
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    false,
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.true.nottrue'
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
//         'constraint.true.nottrue'
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
//         'constraint.true.nottrue'
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
//         'constraint.true.nottrue'
//     )
// ];
```
