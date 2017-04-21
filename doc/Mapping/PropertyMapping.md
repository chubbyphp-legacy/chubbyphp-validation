# PropertyMapping

```php
<?php

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\PropertyMapping;

$propertyMapping = new PropertyMapping('propertyName', [new NotNullConstraint()]);
$propertyMapping->getName(); // 'propertyName'
$propertyMapping->getConstraints(); // [new NotNullConstraint()]
```
