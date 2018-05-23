# ValidatorContext

```php
<?php

use Chubbyphp\Validation\Validator\ValidatorContext;

$request = ...;

$context = new ValidatorContext(['group1']);

print_r($context->getGroups());
// ['group1']
```
