# NotBlankConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new NotBlankConstraint();

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

$errors = $constraint->validate('path.to.property', 'value');
// [];

$errors = $constraint->validate('path.to.property', ['value']);
// [];

$object = new \stdClass;
$object->key = 'value';

$errors = $constraint->validate('path.to.property', $object);
// [];

$errors = $constraint->validate('path.to.property', '');
// [new Error('path.to.property', 'constraint.notblank.blank')];

$errors = $constraint->validate('path.to.property', []);
// [new Error('path.to.property', 'constraint.notblank.blank')];

$errors = $constraint->validate('path.to.property', new \stdClass);
// [new Error('path.to.property', 'constraint.notblank.blank')];
```
