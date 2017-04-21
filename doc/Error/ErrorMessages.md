# ErrorMessages

```php
<?php

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorMessages;

$error = new Error('path.to.property', 'constraint.constraint.invalidtype', ['type' => 'array']);

$errorMessages = new ErrorMessages([$error], function () function (string $key, array $arguments) { return $key; });
$errorMessages->getMessages(); // ['path.to.property' => ['constraint.constraint.invalidtype']]
```
