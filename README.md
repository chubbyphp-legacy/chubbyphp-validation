# chubbyphp-validation

[![CI](https://github.com/chubbyphp/chubbyphp-validation/workflows/CI/badge.svg?branch=master)](https://github.com/chubbyphp/chubbyphp-validation/actions?query=workflow%3ACI)
[![Coverage Status](https://coveralls.io/repos/github/chubbyphp/chubbyphp-validation/badge.svg?branch=master)](https://coveralls.io/github/chubbyphp/chubbyphp-validation?branch=master)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/chubbyphp/chubbyphp-validation/master)](https://dashboard.stryker-mutator.io/reports/github.com/chubbyphp/chubbyphp-validation/master)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-validation/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-validation/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Monthly Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-validation/d/monthly)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)

[![bugs](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=bugs)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![code_smells](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=code_smells)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![coverage](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=coverage)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![duplicated_lines_density](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=duplicated_lines_density)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![ncloc](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=ncloc)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![sqale_rating](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![alert_status](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=alert_status)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![reliability_rating](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![security_rating](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=security_rating)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![sqale_index](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=sqale_index)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)
[![vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=chubbyphp_chubbyphp-validation&metric=vulnerabilities)](https://sonarcloud.io/dashboard?id=chubbyphp_chubbyphp-validation)


## Description

A simple validation.

## Requirements

 * php: ^7.4|^8.0
 * psr/log: ^1.0

## Suggest

 * chubbyphp/chubbyphp-container: ^1.0
 * pimple/pimple: ^3.2.3
 * psr/container: ^1.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-validation][1].

```sh
composer require chubbyphp/chubbyphp-validation "^3.12"
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

 * [ApiProblemErrorMessages][4]
 * [Error][5]
 * [ErrorMessages][6]
 * [NestedErrorMessages][7]

### Mapping

 * [CallableValidationMappingProvider][8]
 * [LazyValidationMappingProvider][9]
 * [ValidationClassMapping][10]
 * [ValidationClassMappingBuilder][11]
 * [ValidationMappingProviderRegistry][12]
 * [ValidationPropertyMapping][13]
 * [ValidationPropertyMappingBuilder][14]

#### ValidationMappingProvider

```php
<?php

namespace MyProject\Model;

final class Model
{
    /**
     * @var array<\DateTime>
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

### ServiceFactory

#### chubbyphp-container

 * [ValidationServiceFactory][15]

#### chubbyphp-laminas-config-factory

 * [ValidationMappingProviderRegistryFactory][16]
 * [ValidatorFactory][17]

### ServiceProvider

 * [ValidationServiceProvider][18]

### Validator

 * [ValidatorContext][19]
 * [ValidatorContextBuilder][20]

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

Dominik Zogg 2021


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

[4]: doc/Error/ApiProblemErrorMessages.md
[5]: doc/Error/Error.md
[6]: doc/Error/ErrorMessages.md
[7]: doc/Error/NestedErrorMessages.md

[8]: doc/Mapping/CallableValidationMappingProvider.md
[9]: doc/Mapping/LazyValidationMappingProvider.md
[10]: doc/Mapping/ValidationClassMapping.md
[11]: doc/Mapping/ValidationClassMappingBuilder.md
[12]: doc/Mapping/ValidationMappingProviderRegistry.md
[13]: doc/Mapping/ValidationPropertyMapping.md
[14]: doc/Mapping/ValidationPropertyMappingBuilder.md

[15]: doc/ServiceFactory/ValidationServiceFactory.md
[16]: doc/ServiceFactory/ValidationMappingProviderRegistryFactory.md
[17]: doc/ServiceFactory/ValidatorFactory.md

[18]: doc/ServiceProvider/ValidationServiceProvider.md

[19]: doc/ValidatorContext.md
[20]: doc/ValidatorContextBuilder.md
