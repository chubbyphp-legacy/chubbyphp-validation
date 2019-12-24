# ApiProblemErrorMessages

```php
<?php

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ApiProblemErrorMessages;

$error = new Error(
    'path.to.property',
    'constraint.constraint.invalidtype',
    ['type' => 'array']
);

$errorMessages = new ApiProblemErrorMessages([$error]);

$errorMessages->getMessages();
// [
//     [
//         'name' => 'path.to.property',
//         'reason' => 'constraint.constraint.invalidtype',
//         'details' => ['type' => 'array'],
//     ],
// ]
```
