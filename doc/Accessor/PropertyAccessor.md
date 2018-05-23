# PropertyAccessor

```php
<?php

use Chubbyphp\Validation\Accessor\PropertyAccessor;
use MyProject\Model;

$object = new Model;
$object->name = 'php';

$accessor = new PropertyAccessor('name');

$accessor->getValue($object);
// 'php'
```
