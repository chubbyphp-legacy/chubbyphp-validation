# EmailConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\EmailConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new EmailConstraint();

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// $errors[];

$errors = $constraint->validate('path.to.property', 'firstname.lastname@domain.tld');
// $errors[];

$errors = $constraint->validate('path.to.property', []);
// $errors[new Error('path.to.property', 'constraint.email.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', 'email');
// $errors[new Error('path.to.property', 'constraint.email.invalidformat', ['input' => 'email'])];
```
