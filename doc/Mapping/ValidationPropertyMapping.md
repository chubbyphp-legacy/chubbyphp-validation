# ValidationPropertyMapping

```php
<?php

use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ValidationPropertyMapping;

$fieldMapping = new ValidationPropertyMapping(
    'name',
    [new NotNullConstraint()],
    ['group1'],
    new PropertyAccessor('name')
);

$fieldMapping->getName();
// 'name'

$fieldMapping->getConstraints();
// [new NotNullConstraint()]

$fieldMapping->getGroups();
// ['group1']

$fieldMapping->getAccessor();
// new PropertyAccessor('name')
```
