# UniqueModelConstraint

```php
<?php

use Chubbyphp\Model\Resolver;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator;
use Chubbyphp\ValidationModel\Constraint\UniqueModelConstraint;
use MyProject\Model\Model;

$resolver = new Resolver(...);
$validator = new Validator(...);

$constraint = new UniqueModelConstraint($resolver, ['name']);

$errors = $constraint->validate('', (new Model())->setName('name'), $validator);
// [];

// same name, second model
$errors = $constraint->validate('', (new Model())->setName('name'), $validator);
// [new Error('name', 'constraint.uniquemodel.notunique', ['uniqueProperties' => 'name'])];

```
