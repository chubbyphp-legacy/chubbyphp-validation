# ValidationProvider

```php
<?php

use Chubbyphp\Validation\Provider\ValidationProvider;
use MyProject\Validation\ModelMapping;
use Pimple\Container;

$container = new Container();
$container->register(new ValidationProvider());

$container->extend('validator.objectmappings', function (array $objectMappings) use ($container) {
    $objectMappings[] = new ModelMapping(...);

    return $objectMappings;
});

$container['validator']->validateObject($model);
```
