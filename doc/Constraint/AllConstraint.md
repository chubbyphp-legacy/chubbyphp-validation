# AllConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\AllConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new AllConstraint([
    new NotNullConstraint(),
    new NotBlankConstraint()
]);

$values = [
    null,
    '',
    'value',
];

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $constraint->validate(
    'path.to.property',
    $values,
    $context
);
// [
//     new Error(
//         'path.to.property[0]',
//         'constraint.notnull.null'
//     ),
//     new Error(
//         'path.to.property[1]',
//         'constraint.notblank.blank'
//     )
// ];
```
