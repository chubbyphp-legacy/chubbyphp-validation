# UniqueModelConstraint

```php
<?php

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator;
use Chubbyphp\ValidationDoctrine\Constraint\UniqueModelConstraint;
use Doctrine\Common\Persistence\ManagerRegistry;
use MyProject\Model\Model;

$registry = new ManagerRegistry(...);
$validator = new Validator(...);

$constraint = new UniqueModelConstraint($registry, ['name']);

$errors = $constraint->validate('', (new Model())->setName('name'), $validator);
// [];

// same name, second model
$errors = $constraint->validate('', (new Model())->setName('name'), $validator);
// [new Error('name', 'constraint.uniquemodel.notunique', ['uniqueProperties' => 'name'])];

```
