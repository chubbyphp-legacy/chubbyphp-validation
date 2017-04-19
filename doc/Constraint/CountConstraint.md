# CountConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CountConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new CountConstraint(1, 2);

$errors = $constraint->validate('path.to.property', '');
// $errors[new Error('path.to.property', 'constraint.count.invalidtype', ['type' => 'string'])];

$errors = $constraint->validate('path.to.property', []);
// $errors[new Error('path.to.property', 'constraint.count.outofrange', ['count' => 0, 'min' => 1, 'max' => 2])];

$errors = $constraint->validate('path.to.property', ['value']);
// $errors[];

$errors = $constraint->validate('path.to.property', ['value', 'value']);
// $errors[];

$errors = $constraint->validate('path.to.property', ['value', 'value', 'value']);
// $errors[new Error('path.to.property', 'constraint.count.outofrange', ['count' => 3, 'min' => 1, 'max' => 2])];
```
