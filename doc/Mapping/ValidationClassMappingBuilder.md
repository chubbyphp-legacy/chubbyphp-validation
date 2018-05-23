# ValidationClassMappingBuilder

```php
<?php

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder;

$fieldMapping = ValidationClassMappingBuilder
    ::create(
        [new NotNullConstraint()]
    )
    ->setGroups(['group1'])
    ->getMapping();

$fieldMapping->getConstraints();
// [new NotNullConstraint()]

$fieldMapping->getGroups();
// ['group1']
```
