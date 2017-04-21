# PropertyMapping

```php
<?php

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\PropertyMapping;

$propertyMapping = new PropertyMapping('name', [new NotNullConstraint()]);
$propertyMapping->getName(); // 'name'
$propertyMapping->getConstraints(); // [new NotNullConstraint()]
```
