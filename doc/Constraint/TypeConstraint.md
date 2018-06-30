# TypeConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\TypeConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new TypeConstraint('string');

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
    'text',
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    1,
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.type.invalidtype',
//         ['type' => 'integer', 'wishedType' => 'string']
//     )
// ];
```
