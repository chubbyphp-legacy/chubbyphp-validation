# CoordinateArrayConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CoordinateArrayConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

$constraint = new CoordinateArrayConstraint();

/** @var ValidatorContextInterface $context */
$context = ...;

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null, $context);
// [];

$errors = $constraint->validate('path.to.property', ['lat' => 90.0, 'lon' => 180.0], $context);
$errors = $constraint->validate('path.to.property', ['lat' => -90.0, 'lon' => -180.0], $context);
// [];

$errors = $constraint->validate('path.to.property', '', $context);
// [new Error('path.to.property', 'constraint.coordinatearray.invalidtype', ['type' => 'string'])];

$errors = $constraint->validate('path.to.property', [], $context);
// [new Error('path.to.property', 'constraint.coordinatearray.invalidformat', ['value' => []])];

$errors = $constraint->validate('path.to.property', ['lat' => 90.1, 'lon' => 180.1], $context);
// [new Error('path.to.property', 'constraint.coordinatearray.invalidvalue', ['value' => ['lat' => 90.1, 'lon' => 180.1])];
```
