# ValidationServiceProvider

```php
<?php

use Chubbyphp\Validation\ServiceProvider\ValidationServiceProvider;
use Chubbyphp\Validation\ValidatorContextInterface;
use Pimple\Container;

$container = new Container();
$container->register(new ValidationServiceProvider);

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
