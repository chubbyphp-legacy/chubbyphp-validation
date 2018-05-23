# ValidationClassMapping

```php
<?php

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ValidationClassMapping;

$fieldMapping = new ValidationClassMapping(
    [new NotNullConstraint()],
    ['group1']
);

$fieldMapping->getConstraints();
// [new NotNullConstraint()]

$fieldMapping->getGroups();
// ['group1']
```
