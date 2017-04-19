# DateConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\DateConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new DateConstraint();

$errors = $constraint->validate('path.to.property', null);
// $errors[];

$errors = $constraint->validate('path.to.property', new \DateTime);
// $errors[];

$errors = $constraint->validate('path.to.property', '2017-01-01');
// $errors[];

$errors = $constraint->validate('path.to.property', []);
// $errors[new Error('path.to.property', 'constraint.date.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', '2017-01-35');
// $errors[new Error('path.to.property', 'constraint.date.invalidformat', ['input' => '2017-01-35'])];
```
