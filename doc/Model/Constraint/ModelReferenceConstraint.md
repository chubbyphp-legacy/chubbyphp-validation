# ModelReferenceConstraint

```php
<?php

use Chubbyphp\Model\Reference\ModelReference;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator;
use Chubbyphp\ValidationModel\Constraint\ModelReferenceConstraint;
use MyProject\Model\Model;

$validator = new Validator(...);

$constraint = new ModelReferenceConstraint(false, true);

$errors = $constraint->validate('path.to.property', new ModelReference(...), $validator);
// [new Error('path.to.property', 'constraint.modelreference.null')];

$errors = $constraint->validate('path.to.property', (new ModelReference(...))->setModel(new Model()), $validator);
// [];

// if there is an error within a child element
$errors = $constraint->validate('path.to.property', (new ModelReference(...))->setModel(new Model()), $validator);
// [new Error('path.to.property.name', 'constraint.notnull.null')];

```
