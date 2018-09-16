<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Mapping\CallableValidationMappingProvider;
use Chubbyphp\Validation\Mapping\ValidationClassMappingInterface;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Chubbyphp\Validation\Mapping\ValidationPropertyMappingInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\CallableValidationMappingProvider
 */
class CallableValidationMappingProviderTest extends TestCase
{
    use MockByCallsTrait;

    public function testGetClass()
    {
        $mapping = new CallableValidationMappingProvider(\stdClass::class, function () {});

        self::assertSame(\stdClass::class, $mapping->getClass());
    }

    public function testGetValidationClassMapping()
    {
        $classMapping = $this->getMockByCalls(ValidationClassMappingInterface::class);

        $mapping = new CallableValidationMappingProvider(\stdClass::class, function () use ($classMapping) {
            return $this->getMockByCalls(ValidationMappingProviderInterface::class, [
                Call::create('getValidationClassMapping')->with('path')->willReturn($classMapping),
            ]);
        });

        self::assertSame($classMapping, $mapping->getValidationClassMapping('path'));
    }

    public function testGetValidationPropertyMappings()
    {
        $propertyMapping = $this->getMockByCalls(ValidationPropertyMappingInterface::class);

        $mapping = new CallableValidationMappingProvider(\stdClass::class, function () use ($propertyMapping) {
            return $this->getMockByCalls(ValidationMappingProviderInterface::class, [
                Call::create('getValidationPropertyMappings')->with('path')->willReturn([$propertyMapping]),
            ]);
        });

        self::assertSame($propertyMapping, $mapping->getValidationPropertyMappings('path')[0]);
    }
}
