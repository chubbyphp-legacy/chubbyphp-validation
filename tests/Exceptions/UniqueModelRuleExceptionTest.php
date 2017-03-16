<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Exceptions;

use Chubbyphp\Validation\Exceptions\UniqueModelRuleException;

/**
 * @covers Chubbyphp\Validation\Exceptions\UniqueModelRuleException
 */
class UniqueModelRuleExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultTemplates()
    {
        self::assertEquals([
            UniqueModelRuleException::MODE_DEFAULT => [
                UniqueModelRuleException::STANDARD => '{{name}} are not unique',
            ],
            UniqueModelRuleException::MODE_NEGATIVE => [
                UniqueModelRuleException::STANDARD => '{{name}} are unique',
            ],
        ], UniqueModelRuleException::$defaultTemplates);
    }
}
