# ValidatorContextBuilder

```php
<?php

use Chubbyphp\Validation\Validator\ValidatorContextBuilder;

$request = ...;

$context = ValidatorContextBuilder::create()
    ->setGroups(['group1'])
    ->getContext();

print_r($context->getGroups());
// ['group1']
```
