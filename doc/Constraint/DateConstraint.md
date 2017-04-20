# DateConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\DateConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new DateConstraint();

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

$errors = $constraint->validate('path.to.property', new \DateTime);
// [];

$errors = $constraint->validate('path.to.property', '2017-01-01');
// [];

$errors = $constraint->validate('path.to.property', []);
// [new Error('path.to.property', 'constraint.date.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', '2017-01-35');
// [new Error('path.to.property', 'constraint.date.invalidformat', ['input' => '2017-01-35'])];
```
