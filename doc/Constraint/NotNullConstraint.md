# NotNullConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new NotNullConstraint();

$errors = $constraint->validate('path.to.property', '');
// [];

$errors = $constraint->validate('path.to.property', null);
// [new Error('path.to.property', 'constraint.notnull.null')];
```
