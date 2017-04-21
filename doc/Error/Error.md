# Error

```php
<?php

use Chubbyphp\Validation\Error\Error;

$error = new Error('path.to.property', 'constraint.constraint.invalidtype', ['type' => 'array']);
$error->getPath(); // 'path.to.property'
$error->getKey(); // 'constraint.constraint.invalidtype'
$error->getArguments(); // ['type' => 'array']
```
