# NumericConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NumericConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

$constraint = new NumericConstraint();

/** @var ValidatorContextInterface $context */
$context = ...;

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null, $context);
// [];

$errors = $constraint->validate('path.to.property', 1, $context);
// [];

$errors = $constraint->validate('path.to.property', 1.1, $context);
// [];

$errors = $constraint->validate('path.to.property', '1.1', $context);
// [];

$errors = $constraint->validate('path.to.property', [], $context);
// [new Error('path.to.property', 'constraint.numeric.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', 'email', $context);
// [new Error('path.to.property', 'constraint.numeric.notnumeric', ['value' => 'email'])];
```
