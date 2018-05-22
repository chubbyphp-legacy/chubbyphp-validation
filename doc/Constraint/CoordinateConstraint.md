# CoordinateConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CoordinateConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

$constraint = new CoordinateConstraint();

/** @var ValidatorContextInterface $context */
$context = ...;

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null, $context);
// [];

$errors = $constraint->validate('path.to.property', '90.0, 180.0', $context);
$errors = $constraint->validate('path.to.property', '-90.0, -180.0', $context);
// [];

$errors = $constraint->validate('path.to.property', [], $context);
// [new Error('path.to.property', 'constraint.coordinate.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', '90', $context);
// [new Error('path.to.property', 'constraint.coordinate.invalidformat', ['value' => '90'])];

$errors = $constraint->validate('path.to.property', '90.1, 180.1', $context);
// [new Error('path.to.property', 'constraint.coordinate.invalidvalue', ['value' => '90.1, 180.1'])];
```
