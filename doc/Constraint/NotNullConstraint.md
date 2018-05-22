# NotNullConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

$constraint = new NotNullConstraint();

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $constraint->validate('path.to.property', '', $context);
// [];

$errors = $constraint->validate('path.to.property', null, $context);
// [new Error('path.to.property', 'constraint.notnull.null')];
```
