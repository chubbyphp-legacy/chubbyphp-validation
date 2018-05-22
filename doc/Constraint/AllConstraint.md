# AllConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\AllConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

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

$errors = $constraint->validate('', $values, $context);

echo count($errors);
// 2

echo $errors[0]['path'];
// [0]

echo $errors[0]['key'];
// constraint.notnull.null'

echo $errors[1]['path'];
// [1]

echo $errors[1]['key'];
// constraint.notblank.blank'

```
