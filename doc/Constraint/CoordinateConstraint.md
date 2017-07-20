# CoordinateConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CoordinateConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new CoordinateConstraint();

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

$errors = $constraint->validate('path.to.property', '90.0, 180.0');
$errors = $constraint->validate('path.to.property', '-90.0, -180.0');
// [];

$errors = $constraint->validate('path.to.property', []);
// [new Error('path.to.property', 'constraint.coordinate.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', '90');
// [new Error('path.to.property', 'constraint.coordinate.invalidformat', ['input' => '90'])];

$errors = $constraint->validate('path.to.property', '90.1, 180.1');
// [new Error('path.to.property', 'constraint.coordinate.invalidvalue', ['input' => '90.1, 180.1'])];
```
