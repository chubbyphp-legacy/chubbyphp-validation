# ValidationProvider

```php
<?php

use Chubbyphp\Validation\Provider\ValidationProvider;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Pimple\Container;

$container = new Container();
$container->register(new ValidationProvider);

$object = ...;

/** @var ValidatorContextInterface $context */
$context = ...;

$container['validator']
    ->validate(
        $object,
        $context,
        'path'
    );
```
