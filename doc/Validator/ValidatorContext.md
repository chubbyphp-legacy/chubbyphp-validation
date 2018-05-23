# ValidatorContext

```php
<?php

use Chubbyphp\Validation\Validator\ValidatorContext;

$request = ...;

$context = new ValidatorContext(['group1']);

$groups = $context->getGroups();
// ['group1']
```
