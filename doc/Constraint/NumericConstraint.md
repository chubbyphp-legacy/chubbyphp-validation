# NumericConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NumericConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new NumericConstraint();

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

$errors = $constraint->validate('path.to.property', 1);
// [];

$errors = $constraint->validate('path.to.property', 1.1);
// [];

$errors = $constraint->validate('path.to.property', '1.1');
// [];

$errors = $constraint->validate('path.to.property', []);
// [new Error('path.to.property', 'constraint.numeric.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', 'email');
// [new Error('path.to.property', 'constraint.numeric.notnumeric', ['input' => 'email'])];
```
