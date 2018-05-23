# ValidationMappingProviderRegistry

```php
<?php

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;

$registry = new ValidationMappingProviderRegistry([]);

$registry
    ->provideMapping(Model::class)
    ->getClass();
// \Namespace\To\Model
```
