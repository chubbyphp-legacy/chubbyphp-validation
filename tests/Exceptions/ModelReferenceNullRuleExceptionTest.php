<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Exceptions;

use Chubbyphp\Validation\Exceptions\ModelReferenceNullRuleException;

/**
 * @covers Chubbyphp\Validation\Exceptions\ModelReferenceNullRuleException
 */
class ModelReferenceNullRuleExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultTemplates()
    {
        self::assertEquals([
            ModelReferenceNullRuleException::MODE_DEFAULT => [
                ModelReferenceNullRuleException::STANDARD => '{{name}} must be null',
            ],
            ModelReferenceNullRuleException::MODE_NEGATIVE => [
                ModelReferenceNullRuleException::STANDARD => '{{name}} must not be null',
            ],
        ], ModelReferenceNullRuleException::$defaultTemplates);
    }
}
