# DateTimeConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\DateTimeConstraint;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;

/** @var ValidatorContextInterface $context */
$context = ...;

$constraint = new DateTimeConstraint();

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null, $context);
// [];

// Use NotBlankConstraint to prevent ''
$errors = $constraint->validate('path.to.property', '', $context);
// [];

$errors = $constraint->validate('path.to.property', '2017-12-01 07:00:00', $context);
// [];

$errors = $constraint->validate('path.to.property', new \DateTime('2017-12-01 07:00:00'), $context);
// [];

$errors = $constraint->validate('path.to.property', new \DateTime('2017-13-01 07:00:00'), $context);
// [new Error('path.to.property','constraint.datetime.error', ['message' => 'The parsed date was invalid', 'format' => 'Y-m-d H:i:s', 'value' => '2017-13-01 07:00:00'])];

$errors = $constraint->validate('path.to.property', new \DateTime('2017-12-01 07:00:00:00'), $context);
// [new Error('path.to.property','constraint.datetime.error', ['message' => 'Trailing data', 'format' => 'Y-m-d H:i:s', 'value' => '2017-12-01 07:00:00:00'])];


$constraint = new DateTimeConstraint('Y-m-d');

// Use NotNullConstraint to prevent null
$errors = $constraint->validate('path.to.property', null, $context);
// [];

// Use NotBlankConstraint to prevent ''
$errors = $constraint->validate('path.to.property', '', $context);
// [];

$errors = $constraint->validate('path.to.property', '2017-12-01', $context);
// [];

$errors = $constraint->validate('path.to.property', new \DateTime('2017-12-01'), $context);
// [];

$errors = $constraint->validate('path.to.property', new \DateTime('2017-13-01'), $context);
// [new Error('path.to.property','constraint.datetime.error', ['message' => 'The parsed date was invalid', 'format' => 'Y-m-d', 'value' => '2017-13-01'])];
```
