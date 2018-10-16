# UniqueConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\UniqueConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;
use Doctrine\Common\Persistence\ObjectManager;

/** @var ObjectManager $objectManager */
$objectManager = ...;

$constraint = new UniqueConstraint($objectManager, ['name']);

/** @var ValidatorContextInterface $context */
$context = ...;

// Use NotNullConstraint to prevent null
$errors = $constraint->validate(
    'path.to.model',
    null,
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.model',
    $model,
    $context
);
// [];

// There is already a model with the same values
$errors = $constraint->validate(
    'path.to.model',
    $model,
    $context
);
// [
//     new Error(
//         'path.to.model.name',
//         'constraint.unique.notunique',
//         ['uniqueProperties' => 'name']
//     )
// ];
