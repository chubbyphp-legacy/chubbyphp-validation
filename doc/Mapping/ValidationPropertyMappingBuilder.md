# ValidationPropertyMappingBuilder

```php
<?php

use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingBuilder;

$fieldMapping = ValidationPropertyMappingBuilder
    ::create(
        'name',
        [new NotNullConstraint()]
    )
    ->setGroups(['group1'])
    ->setAccessor(new PropertyAccessor('name'))
    ->getMapping();

$fieldMapping->getName();
// 'name'

$fieldMapping->getConstraints();
// [new NotNullConstraint()]

$fieldMapping->getGroups();
// ['group1']

$fieldMapping->getAccessor();
// new PropertyAccessor('name')
```
