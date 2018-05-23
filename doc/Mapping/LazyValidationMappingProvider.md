# LazyValidationMappingProvider

```php
<?php

use Chubbyphp\Validation\Mapping\LazyValidationMappingProvider;

$container = ...;

$mappingProvider = new LazyValidationMappingProvider(
    $container,
    'myproject.denormalizer.mapping.model',
    Model::class
);

$mappingProvider->getClass();
// \Namespace\To\Model

$mappingProvider->getValidationClassMapping('');
// ValidationClassMappingInterface[]

$mappingProvider->getValidationPropertyMappings('');
// ValidationPropertyMappingInterface[]
```
