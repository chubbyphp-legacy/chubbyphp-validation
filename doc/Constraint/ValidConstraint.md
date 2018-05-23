# ValidConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\ValidConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\ValidatorInterface;

$constraint = new ValidConstraint();

/** @var ValidatorContextInterface $context */
$context = ...;

/** @var ValidatorInterface $validator */
$validator = ...;

$model = new class()
{
    /**
     * @var string
     */
    private $name;
};

$errors = $constraint->validate('path.to.property', [$model], $context, $validator);
// [new Error('path.to.property[0]['name]', 'constraint.notnull.null')];
```
