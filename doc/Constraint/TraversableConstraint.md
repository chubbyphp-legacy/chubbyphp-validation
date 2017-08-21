# TraversableConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\TraversableConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new TraversableConstraint([], 1, 2);

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

// Use NumericConstraint to prevent not numeric
$errors = $constraint->validate('path.to.property', []);
// [];

$errors = $constraint->validate('path.to.property', []);
// [new Error('path.to.property', 'constraint.traversable.outofrange', ['count' => 0, 'min' => 1, 'max' => 2])];

$errors = $constraint->validate('path.to.property', ['string']);
// [];

$errors = $constraint->validate('path.to.property', ['string', 'string']);
// [];

$errors = $constraint->validate('path.to.property', 3);
// [new Error('path.to.property', 'constraint.traversable.outofrange', ['count' => 3, 'min' => 1, 'max' => 2])];
```
