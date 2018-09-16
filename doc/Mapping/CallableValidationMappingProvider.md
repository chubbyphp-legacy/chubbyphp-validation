# CallableValidationMappingProvider

```php
<?php

use Chubbyphp\Validation\Mapping\CallableValidationMappingProvider;
use MyProject\Mapping\ModelMapping;
use MyProject\Model\Model;

$mappingProvider = new CallableValidationMappingProvider(
    Model::class,
        function () {
        return new ModelMapping();
    }
);

$mappingProvider->getClass();
// \Namespace\To\Model

$mappingProvider->getValidationClassMapping('');
// ValidationClassMappingInterface[]

$mappingProvider->getValidationPropertyMappings('');
// ValidationPropertyMappingInterface[]
```
