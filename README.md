# chubbyphp-validation

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-validation.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-validation)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-validation/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-validation/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/?branch=master)

## Description

A simple validation.

## Requirements

 * php: ~7.0
 * psr/log: ~1.0

## Suggest

 * container-interop/container-interop: ~1.0
 * pimple/pimple: ~3.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-validation][1].

```sh
composer require chubbyphp/chubbyphp-validation "~2.0@alpha"
```

## Usage

### Validator

```php
<?php

use Chubbyphp\Validation\Error\NestedErrorMessages;
use Chubbyphp\Validation\Registry\ObjectMappingRegistry;
use Chubbyphp\Validation\Validator;
use MyProject\Model\Model;
use MyProject\Repository\ModelRepository;
use MyProject\Validation\ModelMapping;

/** @var ModelRepository $modelRepository */
$modelRepository = ...;

$translate = function (string $key, array $arguments) { return $key; };

$validator = new Validator(new ObjectMappingRegistry([new ModelMapping($modelRepository)]));

$model = new Model();

// name is null
$errorMessages = new NestedErrorMessages($validator->validateObject($model), $translate);
$errorMessages->getMessages(); // ['name' => ['constraint.notnull.null']]

$model->setName('name');

// name is not null
$errorMessages = new NestedErrorMessages($validator->validateObject($model), $translate);
$errorMessages->getMessages(); // []
```

### Mapping

```php
<?php

namespace MyProject\Validation;

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use Chubbyphp\Validation\Mapping\PropertyMappingInterface;
use MyProject\Model\Model;
use MyProject\Repository\ModelRepository;
use MyProject\Validation\UniqueModelConstraint;

class ModelMapping implements ObjectMappingInterface
{
    /**
     * @var ModelRepository
     */
    private $modelRepository;

    /**
     * @param ModelRepository $modelRepository
     */
    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

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
        return [new UniqueModelConstraint($this->modelRepository, ['name'])];
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
            new PropertyMapping('name', [new NotNullConstraint()]),
        ];
    }
}
```

 * [LazyObjectMapping][2]
 * [PropertyMapping][3]

### Constraint

* [CallbackConstraint][20]
* [ChoiceConstraint][21]
* [CoordinateArrayConstraint][22]
* [CoordinateConstraint][23]
* [CountConstraint][24]
* [DateConstraint][25]
* [EmailConstraint][26]
* [NotBlankConstraint][27]
* [NotNullConstraint][28]
* [NumericConstraint][29]
* [NumericRangeConstraint][30]

### Error

* [Error][4]
* [ErrorMessages][5]
* [NestedErrorMessages][6]

### Registry

* [ObjectMappingRegistry][7]

### Provider

* [ValidationProvider][8]

## Copyright

Dominik Zogg 2017


[1]: https://packagist.org/packages/chubbyphp/chubbyphp-validation

[2]: doc/Mapping/LazyObjectMapping.md
[3]: doc/Mapping/PropertyMapping.md
[4]: doc/Error/Error.md
[5]: doc/Error/ErrorMessages.md
[6]: doc/Error/NestedErrorMessages.md
[7]: doc/Registry/ObjectMappingRegistry.md
[8]: doc/Provider/ValidationProvider.md

[20]: doc/Constraint/CallbackConstraint.md
[21]: doc/Constraint/ChoiceConstraint.md
[22]: doc/Constraint/CoordinateArrayConstraint.md
[23]: doc/Constraint/CoordinateConstraint.md
[24]: doc/Constraint/CountConstraint.md
[25]: doc/Constraint/DateConstraint.md
[26]: doc/Constraint/EmailConstraint.md
[27]: doc/Constraint/NotBlankConstraint.md
[28]: doc/Constraint/NotNullConstraint.md
[29]: doc/Constraint/NumericConstraint.md
[30]: doc/Constraint/NumericRangeConstraint.md
