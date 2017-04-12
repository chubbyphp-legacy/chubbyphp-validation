<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Tests\Validation\Resources\Model;
use Chubbyphp\Tests\Validation\Resources\ModelMapping;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\Error\Errors;
use Chubbyphp\Validation\Registry\ObjectMappingRegistry;
use Chubbyphp\Validation\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutErrors()
    {
        $objectMappingRegistry = new ObjectMappingRegistry([
            new ModelMapping()
        ]);

        $validator = new Validator($objectMappingRegistry);

        $model = new Model();
        $model->setNotNull('');
        $model->setNotBlank('test');
        $model->setRange(8);

        $errors = $validator->validateObject($model);

        self::assertEquals([], $errors);
    }

    public function testWithErrors()
    {
        $objectMappingRegistry = new ObjectMappingRegistry([
            new ModelMapping()
        ]);

        $validator = new Validator($objectMappingRegistry);

        $model = new Model();
        $model->setNotNull(null);
        $model->setNotBlank('');
        $model->setRange(11);

        $errors = new Errors($validator->validateObject($model));

        self::assertEquals([
            new Error('notNull', 'constraint.notnull'),
            new Error('notBlank', 'constraint.notblank'),
            new Error('range', 'constraint.range.outofrange', ['input' => 11, 'min' => 1, 'max' => 10]),
        ], $errors->getErrors());

    }
}
