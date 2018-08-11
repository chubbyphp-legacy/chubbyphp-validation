# BlankConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\BlankConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new BlankConstraint();

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
// [];

$errors = $constraint->validate(
    'path.to.property',
    [],
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    new \stdClass,
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    'value',
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.blank.notblank'
//     )
// ];

$errors = $constraint->validate(
    'path.to.property',
    ['value'],
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.blank.notblank'
//     )
// ];

$object = new \stdClass();
$object->key = 'value';

$errors = $constraint->validate(
    'path.to.property',
    $object,
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.blank.notblank'
//     )
// ];
```
