# NestedErrorMessages

```php
<?php

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\NestedErrorMessages;

$error = new Error(
    'path.to.property',
    'constraint.constraint.invalidtype',
    ['type' => 'array']
);

$errorMessages = new NestedErrorMessages(
    [$error],
    function (string $key, array $arguments) {
        return $key;
    }
);

$errorMessages->getMessages();
// [
//     'path' => [
//         'to' => [
//             'property' => [
//                 'constraint.constraint.invalidtype'
//             ]
//         ]
//     ]
// ]
```
