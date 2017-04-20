# NumericConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NumericConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new NumericConstraint();

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// $errors[];

$errors = $constraint->validate('path.to.property', 1);
// $errors[];

$errors = $constraint->validate('path.to.property', 1.1);
// $errors[];

$errors = $constraint->validate('path.to.property', '1.1');
// $errors[];

$errors = $constraint->validate('path.to.property', []);
// $errors[new Error('path.to.property', 'constraint.numeric.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', 'email');
// $errors[new Error('path.to.property', 'constraint.numeric.notnumeric', ['input' => 'email'])];
```
