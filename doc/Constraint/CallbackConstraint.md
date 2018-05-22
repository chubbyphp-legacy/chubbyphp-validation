# CallbackConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\CallbackConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

$constraint = new CallbackConstraint(
    function (
        string $path,
        $value,
        ValidatorContextInterface $context,
        ValidatorInterface $validator = null
    ) {
        if ($value !== 'test') {
            return new Error($path, 'key', []); 
        }
        
        return [];
    }
);

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $constraint->validate('', 'test', $context);

echo count($errors);
// 0

$errors = $constraint->validate('', 'nottest', $context);

echo count($errors);
// 1

echo $errors[0]['path'];
//

echo $errors[0]['key'];
// key
```
