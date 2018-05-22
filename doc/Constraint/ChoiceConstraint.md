# ChoiceConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\ChoiceConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

$constraint = new ChoiceConstraint(['s', 'm', 'l', 'xl']);

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $constraint->validate('', null, $context);

echo count($errors);
// 0

$errors = $constraint->validate('', 'm', $context);

echo count($errors);
// 0

$errors = $constraint->validate('', 'xxl', $context);

echo count($errors);
// 1

echo $errors[0]['path'];
//

echo $errors[0]['key'];
// constraint.choice.invalidvalue
```
