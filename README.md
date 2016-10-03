# chubbyphp-validation

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-validation.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-validation)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-validation/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-validation/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/?branch=master)

## Description

A simple validation uses Respect/Validation.

## Requirements

 * php: ~7.0
 * chubbyphp/chubbyphp-model: ~1.0

## Suggest

 * pimple/pimple: ~3.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-validation][1].

## Usage

### Model

```{.php}
<?php

namespace MyProject\Model;

use Chubbyphp\Validation\Rules\UniqueModelRule;
use Chubbyphp\Validation\ValidatableModelInterface;
use Respect\Validation\Validator as RespectValidator;

class MyModel implements ValidatableModelInterface
{
    ...

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this-id;
    }

    /**
     * @return RespectValidator|null
     */
    public function getModelValidator()
    {
        return RespectValidator::create()->addRule(new UniqueModelRule(['email']));
    }

    /**
     * @return RespectValidator[]|array
     */
    public function getPropertyValidators(): array
    {
        return [
            'email' => RespectValidator::notBlank()->email(),
            'password' => RespectValidator::notBlank(),
        ];
    }
}

```

### Repository

```{.php}
<?php

namespace MyProject\Repository;

use Chubbyphp\Model\RepositoryInterface;
use MyProject\Model\MyModel;

class MyRepository implements RepositoryInterface
{
    ...

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return MyModel::class;
    }

    /**
     * @return MyModel|null
     */
    public function findOneBy(array $criteria)
    {
        ...

        return MyModel;
    }
}
```


### Validator

#### Object

```{.php}
<?php

use Chubbyphp\Validation\Validator;
use MyProject\Model\MyModel;
use MyProject\Repository\MyRepository;

$model = new MyModel;

$validator = new Validator([new MyRepository()]);

$errors = $validator->validateModel($model);

```

#### Array

```{.php}
<?php

use Chubbyphp\Validation\Validator;
use MyProject\Model\MyModel;
use MyProject\Repository\MyRepository;
use Respect\Validation\Validator as RespectValidator;

$data = ['email' => '', 'passsword' => ''];

$validators = [
    'email' => RespectValidator::notBlank()->email(),
    'password' => RespectValidator::notBlank(),
];

$validator = new Validator();

$errors = $validator->validateArray($data, $validators);

```

### ValidationProvider (Pimple)

```{.php}
<?php

use Chubbyphp\Validation\Validator;
use Chubbyphp\Validation\ValidationProvider;
use MyProject\Repository\MyRepository;
use Pimple\Container;

$container->register(new ValidationProvider);

$container->extend('validator.repositories', function (array $repositories) use ($container) {
    $repositories[] = new MyRepository;

    return $repositories;
});

/** @var Validator $validator */
$validator = $container['validator'];
```

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-validation

## Copyright

Dominik Zogg 2016
