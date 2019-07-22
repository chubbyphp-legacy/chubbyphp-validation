# MapConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\MapConstraint;
use Chubbyphp\Validation\Constraint\TypeConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new MapConstraint([
    'name' => new TypeConstraint('string'),
]);

$values = [
    'name' => 1,
];

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $constraint->validate(
    'path.to.property',
    $values,
    $context
);
// [
//     new Error(
//         'path.to.property[name]',
//         'constraint.type.invalidtype',
//         ['type' => 'int', 'wishedType' => 'string']
//     )
// ];
```
