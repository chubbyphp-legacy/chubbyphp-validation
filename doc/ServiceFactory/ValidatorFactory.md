# ValidatorFactory

## without name (default)

```php
<?php

use Chubbyphp\Validation\ServiceFactory\ValidatorFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

$factory = new ValidatorFactory();

$normalizer = $factory($container);
```

## with name `default`

```php
<?php

use Chubbyphp\Validation\ServiceFactory\ValidatorFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

$factory = [ValidatorFactory::class, 'default'];

$normalizer = $factory($container);
```
