# ModelCollectionConstraint

```php
<?php

use Chubbyphp\Model\Collection\ModelCollection;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator;
use Chubbyphp\ValidationModel\Constraint\ModelCollectionConstraint;
use MyProject\Model\Model;

$validator = new Validator(...);

$constraint = new ModelCollectionConstraint(true, 1, 2);

$errors = $constraint->validate('path.to.property', new ModelCollection(...), $validator);
// [new Error('path.to.property', 'constraint.modelcollection.outofrange', ['count' => 0, 'min' => 1, 'max' => 2])];

$errors = $constraint->validate('path.to.property', (new ModelCollection(...))->setModels([new Model()]), $validator);
// [];

$errors = $constraint->validate('path.to.property', (new ModelCollection(...))->setModels([new Model(), new Model()]), $validator);
// [];

$errors = $constraint->validate('path.to.property', (new ModelCollection(...))->setModels([new Model(), new Model(), new Model()]), $validator);
// [new Error('path.to.property', 'constraint.modelcollection.outofrange', ['count' => 3, 'min' => 1, 'max' => 2])];

// if there is an error within a child element
$errors = $constraint->validate('path.to.property', (new ModelCollection(...))->setModels([new Model()]), $validator);
// [new Error('path.to.property[0].name', 'constraint.notnull.null')];

```
