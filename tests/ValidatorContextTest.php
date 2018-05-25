<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\ValidatorContext;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\ValidatorContext
 */
class ValidatorContextTest extends TestCase
{
    public function testCreate()
    {
        $context = new ValidatorContext();

        self::assertSame([], $context->getGroups());
    }

    public function testCreateWithOverridenSettings()
    {
        $context = new ValidatorContext(['group1']);

        self::assertSame(['group1'], $context->getGroups());
    }
}
