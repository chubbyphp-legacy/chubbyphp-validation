# CountConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CountConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new CountConstraint(1, 2);

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

$errors = $constraint->validate('path.to.property', '');
// [new Error('path.to.property', 'constraint.count.invalidtype', ['type' => 'string'])];

$errors = $constraint->validate('path.to.property', []);
// [new Error('path.to.property', 'constraint.count.outofrange', ['count' => 0, 'min' => 1, 'max' => 2])];

$errors = $constraint->validate('path.to.property', ['value']);
// [];

$errors = $constraint->validate('path.to.property', ['value', 'value']);
// [];

$errors = $constraint->validate('path.to.property', ['value', 'value', 'value']);
// [new Error('path.to.property', 'constraint.count.outofrange', ['count' => 3, 'min' => 1, 'max' => 2])];
```
