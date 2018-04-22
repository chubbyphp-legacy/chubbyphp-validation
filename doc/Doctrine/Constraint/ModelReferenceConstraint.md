# ModelReferenceConstraint

```php
<?php

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator;
use Chubbyphp\ValidationDoctrine\Constraint\ModelReferenceConstraint;
use MyProject\Model\Model;

$validator = new Validator(...);

$constraint = new ModelReferenceConstraint(false, true);

$errors = $constraint->validate('path.to.property', null, $validator);
// [new Error('path.to.property', 'constraint.modelreference.null')];

$errors = $constraint->validate('path.to.property', new Model(), $validator);
// [];

// if there is an error within a child element
$errors = $constraint->validate('path.to.property', new Model(), $validator);
// [new Error('path.to.property.name', 'constraint.notnull.null')];

```
