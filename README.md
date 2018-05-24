# chubbyphp-validation

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-validation.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-validation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/?branch=master)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-validation/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Monthly Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-validation/d/monthly)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-validation/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Latest Unstable Version](https://poser.pugx.org/chubbyphp/chubbyphp-validation/v/unstable)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)

## Description

A simple validation.

## Requirements

 * php: ~7.0
 * psr/log: ~1.0

## Suggest

 * psr/container: ~1.0
 * pimple/pimple: ~3.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-validation][1].

```sh
composer require chubbyphp/chubbyphp-validation "~3.0"
```

## Usage

### Accessor

 * [MethodAccessor][2]
 * [PropertyAccessor][3]
 
### Constraint

 * [AllConstraint][101]
 * [CallbackConstraint][102]
 * [ChoiceConstraint][103]
 * [CoordinateArrayConstraint][104]
 * [CoordinateConstraint][105]
 * [CountConstraint][106]
 * [DateTimeConstraint][107]
 * [EmailConstraint][108]
 * [NotBlankConstraint][109]
 * [NotNullConstraint][110]
 * [NumericConstraint][112]
 * [NumericRangeConstraint][112]
 * [ValidConstraint][113]

### Error

 * [Error][4]
 * [ErrorMessages][5]
 * [NestedErrorMessages][6]
 
### Mapping

 * [LazyValidationMappingProvider][7]
 * [ValidationClassMapping][8]
 * [ValidationClassMappingBuilder][9]
 * [ValidationMappingProviderRegistry][10]
 * [ValidationPropertyMapping][11]
 * [ValidationPropertyMappingBuilder][12]

### Provider

 * [ValidationProvider][13]

### Validator

 * [ValidatorContext][14]
 * [ValidatorContextBuilder][15]

```php
<?php

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\Validator\ValidatorContextInterface;
use Chubbyphp\Validation\Validator;

$logger = ...;

$validator = new Validator(
    new ValidationMappingProviderRegistry([
        new ModelMapping()
    ]),
    $logger
);

$model = new Model;

/** @var ValidatorContextInterface $context */
$context = ...;

$model = $validator->validate(
    $model,
    $context
);
```

## Copyright

Dominik Zogg 2018


[1]: https://packagist.org/packages/chubbyphp/chubbyphp-validation

[2]: doc/Accessor/MethodAccessor.md
[3]: doc/Accessor/PropertyAccessor.md

[101]: doc/Constraint/AllConstraint.md
[102]: doc/Constraint/CallbackConstraint.md
[103]: doc/Constraint/ChoiceConstraint.md
[104]: doc/Constraint/CoordinateArrayConstraint.md
[105]: doc/Constraint/CoordinateConstraint.md
[106]: doc/Constraint/CountConstraint.md
[107]: doc/Constraint/DateTimeConstraint.md
[108]: doc/Constraint/EmailConstraint.md
[109]: doc/Constraint/NotBlankConstraint.md
[110]: doc/Constraint/NotNullConstraint.md
[112]: doc/Constraint/NumericConstraint.md
[112]: doc/Constraint/NumericRangeConstraint.md
[113]: doc/Constraint/ValidConstraint.md

[4]: doc/Error/Error.md
[5]: doc/Error/ErrorMessages.md
[6]: doc/Error/NestedErrorMessages.md

[7]: doc/Mapping/LazyValidationMappingProvider.md
[8]: doc/Mapping/ValidationClassMapping.md
[9]: doc/Mapping/ValidationClassMappingBuilder.md
[10]: doc/Mapping/ValidationMappingProviderRegistry.md
[11]: doc/Mapping/ValidationPropertyMapping.md
[12]: doc/Mapping/ValidationPropertyMappingBuilder.md

[13]: doc/Provider/ValidationProvider.md

[14]: doc/Validator/ValidatorContext.md
[15]: doc/Validator/ValidatorContextBuilder.md
