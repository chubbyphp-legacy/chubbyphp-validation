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
 * chubbyphp/chubbyphp-translation: ~1.0

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
use Respect\Validation\Validator as v;

class MyModel implements ValidatableModelInterface
{
    ...

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return v|null
     */
    public function getModelValidator()
    {
        return v::create()->addRule(new UniqueModelRule(['email']));
    }

    /**
     * @return v[]|array
     */
    public function getPropertyValidators(): array
    {
        return [
            'email' => v::notBlank()->email(),
            'password' => v::notBlank(),
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

##### Without translator

```{.php}
<?php

use Chubbyphp\Validation\Requirements\Repository;
use Chubbyphp\Validation\Validator;
use MyProject\Model\MyModel;
use MyProject\Repository\MyRepository;

$model = new MyModel;

$validator = new Validator([new Repository(new MyRepository())]);

$errors = $validator->validateModel($model);
```

##### With translator

```{.php}
<?php

use Chubbyphp\Translator\Translator;
use Chubbyphp\Validation\Requirements\Repository;
use Chubbyphp\Validation\Validator;
use MyProject\Model\MyModel;
use MyProject\Repository\MyRepository;

$model = new MyModel;

$translator = new Translator();
$validator = new Validator([new Repository(new MyRepository())], $translator);

$errors = $validator->validateModel($model);
```

#### Array

##### Without translator

```{.php}
<?php

use Chubbyphp\Validation\Validator;
use MyProject\Model\MyModel;
use MyProject\Repository\MyRepository;
use Respect\Validation\Validator as v;

$data = ['email' => '', 'passsword' => ''];

$validators = [
    'email' => v::notBlank()->email(),
    'password' => v::notBlank(),
];

$validator = new Validator();

$errors = $validator->validateArray($data, $validators);
```

##### With translator

```{.php}
<?php

use Chubbyphp\Translator\Translator;
use Chubbyphp\Validation\Validator;
use MyProject\Model\MyModel;
use MyProject\Repository\MyRepository;
use Respect\Validation\Validator as v;

$data = ['email' => '', 'passsword' => ''];

$validators = [
    'email' => v::notBlank()->email(),
    'password' => v::notBlank(),
];

$translator = new Translator();

$validator = new Validator([], $translator);

$errors = $validator->validateArray($data, $validators);
```

### ValidationProvider (Pimple)

```{.php}
<?php

use Chubbyphp\Validation\Requirements\Repository;
use Chubbyphp\Validation\Validator;
use Chubbyphp\Validation\ValidationProvider;
use MyProject\Repository\MyRepository;
use Pimple\Container;

$container->register(new ValidationProvider);

$container->extend('validator.helpers', function (array $helpers) use ($container) {
    $helpers[] = new Repository(new MyRepository())

    return $helpers;
});


/** @var Validator $validator */
$validator = $container['validator'];
```

[1]: https://packagist.org/packages/chubbyphp/chubbyphp-validation

## Copyright

Dominik Zogg 2016
