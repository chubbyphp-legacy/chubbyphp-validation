<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\ValidatorContextBuilder;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\ValidatorContextBuilder
 */
class ValidatorContextBuilderTest extends TestCase
{
    public function testCreate()
    {
        $context = ValidatorContextBuilder::create()->getContext();

        self::assertInstanceOf(ValidatorContextInterface::class, $context);

        self::assertSame([], $context->getGroups());
    }

    public function testCreateWithOverridenSettings()
    {
        $context = ValidatorContextBuilder::create()
            ->setGroups(['group1'])
            ->getContext();

        self::assertInstanceOf(ValidatorContextInterface::class, $context);

        self::assertSame(['group1'], $context->getGroups());
    }
}
