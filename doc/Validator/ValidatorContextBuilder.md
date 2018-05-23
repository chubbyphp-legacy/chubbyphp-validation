# ValidatorContextBuilder

```php
<?php

use Chubbyphp\Validation\Validator\ValidatorContextBuilder;

$request = ...;

$context = ValidatorContextBuilder::create()
    ->setGroups(['group1'])
    ->getContext();

$groups = $context->getGroups();
// ['group1']
```
