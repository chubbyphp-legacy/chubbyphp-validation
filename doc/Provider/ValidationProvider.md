# ValidationProvider

```php
<?php

use Chubbyphp\Validation\Provider\ValidationProvider;
use Chubbyphp\Validation\ValidatorContextInterface;
use Pimple\Container;

$container = new Container();
$container->register(new ValidationProvider);

$object = ...;

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $container['validator']
    ->validate(
        $object,
        $context,
        'path'
    );
```
