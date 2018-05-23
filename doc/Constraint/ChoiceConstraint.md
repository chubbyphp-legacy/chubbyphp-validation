# ChoiceConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\ChoiceConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

$constraint = new ChoiceConstraint(['active', 'inactive']);

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
    'active',
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    'inactive',
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    [],
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.choice.invalidtype',
//         ['type' => 'array']
//     )
// ];

$errors = $constraint->validate(
    'path.to.property',
    'choice',
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.choice.invalidformat',
//         [
//             'value' => 'choice',
//             'choices' => ['active', 'inactive']
//           ]
//     )
// ];
```
