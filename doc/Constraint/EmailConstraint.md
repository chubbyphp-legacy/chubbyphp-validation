# EmailConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\EmailConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new EmailConstraint();

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

$errors = $constraint->validate(
    'path.to.property',
    'firstname.lastname@domain.tld',
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
//         'constraint.email.invalidtype',
//         ['type' => 'array']
//     )
// ];

$errors = $constraint->validate(
    'path.to.property',
    'email',
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.email.invalidformat',
//         ['value' => 'email']
//     )
// ];
```
