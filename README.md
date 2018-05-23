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

### Error
 
### Mapping

### Provider

### Validator

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
