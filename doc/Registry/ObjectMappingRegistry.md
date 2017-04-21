# ObjectMappingRegistry

```php
<?php

use Chubbyphp\Validation\Registry\ObjectMappingRegistry;
use MyProject\Model\Model;
use MyProject\Validation\ModelMapping;

$objectMappingRegistry = new ObjectMappingRegistry([new ModelMapping]);
$objectMappingRegistry->getObjectMappingForClass(Model::class); // new ModelMapping()
```
