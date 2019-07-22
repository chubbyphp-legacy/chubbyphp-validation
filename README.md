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
composer require chubbyphp/chubbyphp-validation "~3.4"
```

## Usage

### Accessor

 * [MethodAccessor][2]
 * [PropertyAccessor][3]

### Constraint

 * [AllConstraint][101]
 * [BlankConstraint][102]
 * [CallbackConstraint][103]
 * [ChoiceConstraint][104]
 * [CoordinateArrayConstraint][105]
 * [CoordinateConstraint][106]
 * [CountConstraint][107]
 * [DateTimeConstraint][108]
 * [EmailConstraint][109]
 * [FalseConstraint][110]
 * [MapConstraint][111]
 * [NotBlankConstraint][112]
 * [NotNullConstraint][113]
 * [NullConstraint][114]
 * [NumericConstraint][115]
 * [NumericRangeConstraint][116]
 * [TrueConstraint][117]
 * [TypeConstraint][118]
 * [ValidConstraint][119]

#### Doctrine

 * [UniqueConstraint][120]

#### Symfony

 * [ConstraintAdapter][121]

### Error

 * [Error][4]
 * [ErrorMessages][5]
 * [NestedErrorMessages][6]

### Mapping

 * [CallableValidationMappingProvider][7]
 * [LazyValidationMappingProvider][8]
 * [ValidationClassMapping][9]
 * [ValidationClassMappingBuilder][10]
 * [ValidationMappingProviderRegistry][11]
 * [ValidationPropertyMapping][12]
 * [ValidationPropertyMappingBuilder][13]

#### ValidationMappingProvider

```php
<?php

namespace MyProject\Model;

final class Model
{
    /**
     * @var \DateTime[]
     */
    private $dates;
}
```

```php
<?php

namespace MyProject\Mapping\Validation;

use Chubbyphp\Validation\Constraint\AllConstraint;
use Chubbyphp\Validation\Constraint\DateTimeConstraint;
use Chubbyphp\Validation\Constraint\NotBlankConstraint;
use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ValidationClassMappingBuilder;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingBuilder;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use MyProject\Model\Model;

final class ModelValidationMappingProvider implements ValidationMappingProviderInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Model::class;
    }

    /**
     * @param string $path
     *
     * @return ValidationClassMappingInterface
     */
    public function getValidationClassMapping(string $path): ValidationClassMappingInterface
    {
        return ValidationClassMappingBuilder::create([])->getMapping();
    }

    /**
     * @param string      $path
     * @param string|null $type
     *
     * @return ValidationPropertyMappingInterface[]
     */
    public function getValidationPropertyMappings(string $path, string $type = null): array
    {
        return [
            ValidationPropertyMappingBuilder::create('dates', [
                new AllConstraint([
                    new NotNullConstraint(),
                    new NotBlankConstraint(),
                    new DateTimeConstraint('d.m.Y'),
                ]),
            ])->getMapping(),
        ];
    }
}
```

### Provider

 * [ValidationProvider][14]

### Validator

 * [ValidatorContext][15]
 * [ValidatorContextBuilder][16]

```php
<?php

namespace MyProject;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\ValidatorContextInterface;
use Chubbyphp\Validation\Validator;
use MyProject\Mapping\Validation\ModelValidationMappingProvider;
use MyProject\Model\Model;

$logger = ...;

$validator = new Validator(
    new ValidationMappingProviderRegistry([
        new ModelValidationMappingProvider()
    ]),
    $logger
);

$model = new Model;

/** @var ValidatorContextInterface $context */
$context = ...;

$errors = $validator->validate(
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
[102]: doc/Constraint/BlankConstraint.md
[103]: doc/Constraint/CallbackConstraint.md
[104]: doc/Constraint/ChoiceConstraint.md
[105]: doc/Constraint/CoordinateArrayConstraint.md
[106]: doc/Constraint/CoordinateConstraint.md
[107]: doc/Constraint/CountConstraint.md
[108]: doc/Constraint/DateTimeConstraint.md
[109]: doc/Constraint/EmailConstraint.md
[110]: doc/Constraint/FalseConstraint.md
[111]: doc/Constraint/MapConstraint.md
[112]: doc/Constraint/NotBlankConstraint.md
[113]: doc/Constraint/NotNullConstraint.md
[114]: doc/Constraint/NullConstraint.md
[115]: doc/Constraint/NumericConstraint.md
[116]: doc/Constraint/NumericRangeConstraint.md
[117]: doc/Constraint/TrueConstraint.md
[118]: doc/Constraint/TypeConstraint.md
[119]: doc/Constraint/ValidConstraint.md

[120]: doc/Constraint/Doctrine/UniqueConstraint.md

[121]: doc/Constraint/Symfony/ConstraintAdapter.md

[4]: doc/Error/Error.md
[5]: doc/Error/ErrorMessages.md
[6]: doc/Error/NestedErrorMessages.md

[7]: doc/Mapping/CallableValidationMappingProvider.md
[8]: doc/Mapping/LazyValidationMappingProvider.md
[9]: doc/Mapping/ValidationClassMapping.md
[10]: doc/Mapping/ValidationClassMappingBuilder.md
[11]: doc/Mapping/ValidationMappingProviderRegistry.md
[12]: doc/Mapping/ValidationPropertyMapping.md
[13]: doc/Mapping/ValidationPropertyMappingBuilder.md

[14]: doc/Provider/ValidationProvider.md

[15]: doc/ValidatorContext.md
[16]: doc/ValidatorContextBuilder.md
