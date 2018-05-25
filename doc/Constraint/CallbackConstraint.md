# CallbackConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CallbackConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

$constraint = new CallbackConstraint(
    function (
        string $path,
        $value,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        if (null === $value){
            return [];
        }

        return [
            new Error(
                $path,
                'constraint.callback',
                ['value' => $value]
            )
        ];
    }
);

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $constraint->validate(
    'path.to.property',
    null,
    $context
);
// [];

$errors = $constraint->validate(
    'path.to.property',
    'value',
    $context
);
// [
//     new Error(
//         'path.to.property',
//         'constraint.callback',
//         ['value' => 'value']
//     )
// ];
```
