# chubbyphp-validation

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-validation.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-validation)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-validation/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-validation/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/?branch=master)

## Description

A simple validation.

## Requirements

 * php: ~7.0

## Suggest

 * pimple/pimple: ~3.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-validation][1].

## Usage

### Constraint

#### NotNullConstraint

```php
<?php

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Error\Error;

$constraint = new NotNullConstraint();

$errors = $constraint->validate('path.to.property', '');
// $errors[];

$errors = $constraint->validate('path.to.property', null);
// $errors[new Error('path.to.property', 'constraint.notnull.null')];
```

#### List

* [CountConstraint][2]
* [DateConstraint][3]
* [EmailConstraint][4]
* [NotBlankConstraint][5]
* [NotNullConstraint][6]
* [NumericConstraint][7]
* [NumericRangeConstraint][8]

### Error

#### Error

```php
<?php

use Chubbyphp\Validation\Error\Error;

$error = new Error('path.to.property', 'constraint.constraint.invalidtype', ['type' => 'array']);
$error->getPath(); // 'path.to.property'
$error->getKey(); // 'constraint.constraint.invalidtype'
$error->getArguments(); // ['type' => 'array']
```

#### ErrorMessages

```php
<?php

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\ErrorMessages;

$error = new Error('path.to.property', 'constraint.constraint.invalidtype', ['type' => 'array']);

$errorMessages = new ErrorMessages([$error], function () function (string $key, array $arguments) { return $key; });
$errorMessages->getMessages(); // ['path.to.property' => ['constraint.constraint.invalidtype']]
```

#### NestedErrorMessages

```php
<?php

use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\NestedErrorMessages;

$error = new Error('path.to.property', 'constraint.constraint.invalidtype', ['type' => 'array']);

$errorMessages = new NestedErrorMessages([$error], function () function (string $key, array $arguments) { return $key; });
$errorMessages->getMessages(); // ['path' => ['to' => ['property' => ['constraint.constraint.invalidtype']]]]
```

### Mapping

#### PropertyMapping

```php
<?php

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\PropertyMapping;

$propertyMapping = new PropertyMapping('propertyName', [new NotNullConstraint()]);
$propertyMapping->getName(); // 'propertyName'
$propertyMapping->getConstraints(); // [new NotNullConstraint()]
```

#### ObjectMapping (ObjectMappingInterface)

```php
<?php

namespace MyProject\Validation;

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use Chubbyphp\Validation\Mapping\PropertyMappingInterface;
use MyProject\Model\Model;

class ModelMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Model::class;
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array
    {
        return [];
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
            new PropertyMapping('propertyName', [new NotNullConstraint()]),
        ];
    }
}
```

### Registry

#### ObjectMappingRegistry

```php
<?php

use Chubbyphp\Validation\Registry\ObjectMappingRegistry;
use MyProject\Model\Model;
use MyProject\Validation\ModelMapping;

$objectMappingRegistry = new ObjectMappingRegistry([new ModelMapping]);
$objectMappingRegistry->getObjectMappingForClass(Model::class); // new ModelMapping()
```

## Copyright

Dominik Zogg 2017

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-validation
[2]: doc/Constraint/CountConstraint.md
[3]: doc/Constraint/DateConstraint.md
[4]: doc/Constraint/EmailConstraint.md
[5]: doc/Constraint/NotBlankConstraint.md
[6]: doc/Constraint/NotNullConstraint.md
[7]: doc/Constraint/NumericConstraint.md
[8]: doc/Constraint/NumericRangeConstraint.md
