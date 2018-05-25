# ValidatorContextBuilder

```php
<?php

use Chubbyphp\Validation\ValidatorContextBuilder;

$request = ...;

$context = ValidatorContextBuilder::create()
    ->setGroups(['group1'])
    ->getContext();

$groups = $context->getGroups();
// ['group1']
```
