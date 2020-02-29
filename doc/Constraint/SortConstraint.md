# SortConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\SortConstraint;
use Chubbyphp\Validation\ValidatorContextInterface;

$constraint = new SortConstraint(['name']);

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $constraint->validate('path.to.property', ['name' => 'test'], $context)

// [
//     new Error(
//         'path.to.property',
//         'constraint.sort.order.notallowed',
//         ['field' => 'name', 'order' => 'test', 'allowedOrders' => ['asc', 'desc']]
//     )
// ]
```
