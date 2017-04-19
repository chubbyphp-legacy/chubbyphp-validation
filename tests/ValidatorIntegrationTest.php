<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Tests\Validation\Resources\Model;
use Chubbyphp\Tests\Validation\Resources\ModelMapping;
use Chubbyphp\Validation\Error\ErrorMessages;
use Chubbyphp\Validation\Registry\ObjectMappingRegistry;
use Chubbyphp\Validation\Validator;

/**
 * @coversNothing
 */
class ValidatorIntegrationTest extends \PHPUnit_Framework_TestCase
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

        $errorMessages = new ErrorMessages($validator->validateObject($model), function (string $key, array $arguments) {
            return $key;
        });

        $messages = $errorMessages->getMessages();

        self::assertEquals([
            'notNull' => ['constraint.notnull.null'],
            'notBlank' => ['constraint.notblank.blank'],
            'range' => ['constraint.range.outofrange'],
        ], $messages);
    }
}
