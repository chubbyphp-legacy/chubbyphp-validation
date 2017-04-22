# LazyObjectMapping

```php
<?php

use Chubbyphp\Validation\LazyObjectMapping;
use MyProject\Model\Model;
use MyProject\Repository\ModelRepository;
use MyProject\Validation\ModelMapping;

$container[ModelRepository::class] = function () {
    return new ModelRepository(...)];
};

$container[ModelMapping::class] = function () use ($container) {
    return new ModelMapping($container[ModelRepository::class])];
};

$lazyObjectMapping = new LazyObjectMapping($container, ModelMapping::class, Model::class);
```
