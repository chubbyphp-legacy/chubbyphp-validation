<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Validation\ValidatorLogicException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\ValidatorLogicException
 */
class ValidatorLogicExceptionTest extends TestCase
{
    public function testCreateMissingDenormalizer()
    {
        $exception = ValidatorLogicException::createMissingValidator('path1');

        self::assertSame('There is no validator at path: "path1"', $exception->getMessage());
    }

    public function testCreateMissingMapping()
    {
        $exception = ValidatorLogicException::createMissingMapping(\stdClass::class);

        self::assertSame('There is no mapping for class: "stdClass"', $exception->getMessage());
    }

    public function testCreateMissingMethod()
    {
        $exception = ValidatorLogicException::createMissingMethod(\stdClass::class, ['getName', 'hasName']);

        self::assertSame(
            'There are no accessible method(s) "getName", "hasName", within class: "stdClass"',
            $exception->getMessage()
        );
    }

    public function testCreateMissingProperty()
    {
        $exception = ValidatorLogicException::createMissingProperty(\stdClass::class, 'name');

        self::assertSame('There is no property "name" within class: "stdClass"', $exception->getMessage());
    }
}
