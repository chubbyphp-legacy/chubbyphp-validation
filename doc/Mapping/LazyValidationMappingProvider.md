# LazyValidationMappingProvider

```php
<?php

use Chubbyphp\Validation\Mapping\LazyValidationMappingProvider;

$container = ...;

$mappingProvider = new LazyValidationMappingProvider(
    $container,
    'myproject.denormalizer.mapping.model',
    'class'
);

$mappingProvider->getClass();
// 'class'

$mappingProvider->getValidationClassMapping('');
// ValidationClassMappingInterface[]

$mappingProvider->getValidationPropertyMappings('');
// ValidationPropertyMappingInterface[]
```
