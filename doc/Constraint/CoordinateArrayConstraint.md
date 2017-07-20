# CoordinateArrayConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CoordinateArrayConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new CoordinateArrayConstraint();

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

$errors = $constraint->validate('path.to.property', ['lat' => 90.0, 'lon' => 180.0]);
$errors = $constraint->validate('path.to.property', ['lat' => -90.0, 'lon' => -180.0]);
// [];

$errors = $constraint->validate('path.to.property', '');
// [new Error('path.to.property', 'constraint.coordinatearray.invalidtype', ['type' => 'string'])];

$errors = $constraint->validate('path.to.property', []);
// [new Error('path.to.property', 'constraint.coordinatearray.invalidformat', ['input' => []])];

$errors = $constraint->validate('path.to.property', ['lat' => 90.1, 'lon' => 180.1]);
// [new Error('path.to.property', 'constraint.coordinatearray.invalidvalue', ['input' => ['lat' => 90.1, 'lon' => 180.1])];
```
