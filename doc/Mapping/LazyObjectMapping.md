# LazyObjectMapping

```php
<?php

use Chubbyphp\Validation\LazyObjectMapping;
use MyProject\Model\Model;
use MyProject\Repository\ModelRepository;
use MyProject\Validation\ModelMapping;

$container['service'] = function () {
    return new ModelMapping($modelRepository)];
};

$lazyObjectMapping = new LazyObjectMapping($container, 'service', Model::class);
```
