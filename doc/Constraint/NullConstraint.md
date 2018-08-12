# NullConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NullConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new NullConstraint();

/** @var ValidatorContextInterface $context */
$context = ...;

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
//         'constraint.null.notnull'
//     )
// ];
```
