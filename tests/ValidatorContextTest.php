<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\ValidatorContext;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\ValidatorContext
 *
 * @internal
 */
final class ValidatorContextTest extends TestCase
{
    public function testCreate(): void
    {
        $context = new ValidatorContext();

        self::assertSame([], $context->getGroups());
    }

    public function testCreateWithOverridenSettings(): void
    {
        $context = new ValidatorContext(['group1']);

        self::assertSame(['group1'], $context->getGroups());
    }
}
