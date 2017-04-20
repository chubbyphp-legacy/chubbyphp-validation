# NumericRangeConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NumericRangeConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new NumericRangeConstraint(1, 2);

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

// Use NumericConstraint to prevent not numeric
$errors = $constraint->validate('path.to.property', '');
// [];

$errors = $constraint->validate('path.to.property', 0);
// [new Error('path.to.property', 'constraint.numericrange.outofrange', ['input' => 0, 'min' => 1, 'max' => 2])];

$errors = $constraint->validate('path.to.property', 1);
// [];

$errors = $constraint->validate('path.to.property', 2);
// [];

$errors = $constraint->validate('path.to.property', 3);
// [new Error('path.to.property', 'constraint.numericrange.outofrange', ['input' => 3, 'min' => 1, 'max' => 2])];
```
