# MethodAccessor

```php
<?php

use Chubbyphp\Validation\Accessor\MethodAccessor;
use MyProject\Model;

$object = new Model;
$object->setName('php');

$accessor = new MethodAccessor('name');

$accessor->getValue($object);
// 'php'
```
