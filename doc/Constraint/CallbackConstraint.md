# CallbackConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CallbackConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorInterface;

$constraint = new CallbackConstraint(function (string $path, $input, ValidatorInterface $validator = null) {
    if (null === $input){
        return [];
    }

    return [
        new Error($path, 'constrain.callback', ['input' => $input])
    ];
});

$errors = $constraint->validate('path.to.property', null);
// [];

$errors = $constraint->validate('path.to.property', 'value');
// [new Error('path.to.property', 'constraint.callback', ['input' => 'value'])];
```
