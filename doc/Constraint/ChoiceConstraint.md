# ChoiceConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\ChoiceConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_STRING, ['active', 'inactive']);

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null);
// [];

$errors = $constraint->validate('path.to.property', 'active');
$errors = $constraint->validate('path.to.property', 'inactive');
// [];

$errors = $constraint->validate('path.to.property', []);
// [new Error('path.to.property', 'constraint.choice.invalidtype', ['type' => 'array'])];

$errors = $constraint->validate('path.to.property', 'choice');
// [new Error('path.to.property', 'constraint.choice.invalidformat', ['input' => 'choice', 'choices' => ['active', 'inactive'])];

// allowStringCompare, needed for form input comparsion, cause they are always strings
$constraint = new ChoiceConstraint(ChoiceConstraint::TYPE_FLOAT, [1.0, 2.0], true);

$errors = $constraint->validate('path.to.property', '1.0');
$errors = $constraint->validate('path.to.property', '2.0');
// [];

$errors = $constraint->validate('path.to.property', '3.0');
// [new Error('path.to.property', 'constraint.choice.invalidformat', ['input' => '3.0', 'choices' => ['1.0', '2.0'])];

```
