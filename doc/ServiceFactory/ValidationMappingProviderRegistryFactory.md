# ValidationMappingProviderRegistryFactory

## without name (default)

```php
<?php

use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\ServiceFactory\ValidationMappingProviderRegistryFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

// $container->get(ValidationMappingProviderInterface::class.'[]')

$factory = new ValidationMappingProviderRegistryFactory();

$validationMappingProviderRegistry = $factory($container);
```

## with name `default`

```php
<?php

use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\ServiceFactory\ValidationMappingProviderRegistryFactory;
use Psr\Container\ContainerInterface;

/** @var ContainerInterface $container */
$container = ...;

// $container->get(ValidationMappingProviderInterface::class.'[]default')

$factory = [ValidationMappingProviderRegistryFactory::class, 'default'];

$validationMappingProviderRegistry = $factory($container);
```
