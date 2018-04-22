# ModelCollectionConstraint

```php
<?php


use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator;
use Chubbyphp\ValidationDoctrine\Constraint\ModelCollectionConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use MyProject\Model\Model;

$validator = new Validator(...);

$constraint = new ModelCollectionConstraint(true, 1, 2);

$errors = $constraint->validate('path.to.property', new ArrayCollection(...), $validator);
// [new Error('path.to.property', 'constraint.modelcollection.outofrange', ['count' => 0, 'min' => 1, 'max' => 2])];

$errors = $constraint->validate('path.to.property', (new ArrayCollection(...))->add(new Model()), $validator);
// [];

$errors = $constraint->validate('path.to.property', (new ArrayCollection(...))->add(new Model())->add(new Model()), $validator);
// [];

$errors = $constraint->validate('path.to.property', (new ArrayCollection(...))->add(new Model())->add(new Model())->add(new Model()), $validator);
// [new Error('path.to.property', 'constraint.modelcollection.outofrange', ['count' => 3, 'min' => 1, 'max' => 2])];

// if there is an error within a child element
$errors = $constraint->validate('path.to.property', (new ArrayCollection(...))->add(new Model()), $validator);
// [new Error('path.to.property[0].name', 'constraint.notnull.null')];

```
