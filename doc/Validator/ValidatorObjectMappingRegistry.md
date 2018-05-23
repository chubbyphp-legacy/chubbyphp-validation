# ValidatorObjectMappingRegistry

```php
<?php

use Chubbyphp\Validation\Validator\ValidatorObjectMappingRegistry;

$registry = new ValidatorObjectMappingRegistry([]);

echo $registry->getObjectMapping('class')->getClass();
// 'class'
```
