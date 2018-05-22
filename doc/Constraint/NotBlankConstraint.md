# NotBlankConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

$constraint = new NotBlankConstraint();

/** @var ValidatorContextInterface $context */
$context = ...;

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null, $context);
// [];

$errors = $constraint->validate('path.to.property', 'value', $context);
// [];

$errors = $constraint->validate('path.to.property', ['value'], $context);
// [];

$object = new \stdClass;
$object->key = 'value';

$errors = $constraint->validate('path.to.property', $object, $context);
// [];

$errors = $constraint->validate('path.to.property', '', $context);
// [new Error('path.to.property', 'constraint.notblank.blank')];

$errors = $constraint->validate('path.to.property', [], $context);
// [new Error('path.to.property', 'constraint.notblank.blank')];

$errors = $constraint->validate('path.to.property', new \stdClass, $context);
// [new Error('path.to.property', 'constraint.notblank.blank')];
```
