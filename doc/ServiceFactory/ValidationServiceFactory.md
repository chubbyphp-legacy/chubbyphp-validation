# ValidationServiceFactory

```php
<?php

use Chubbyphp\Container\Container;
use Chubbyphp\Validation\ServiceFactory\ValidationServiceFactory;
use Chubbyphp\Validation\ValidatorContextInterface;

$container = new Container();
$container->factories((new ValidationServiceFactory())());

$object = ...;

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $container->get('validator')
    ->validate(
        $object,
        $context,
        'path'
    );
```
