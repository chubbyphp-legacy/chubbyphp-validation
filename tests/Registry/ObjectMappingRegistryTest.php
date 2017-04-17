<?php

namespace Chubbyphp\Tests\Validation\Registry;

use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Registry\ObjectMappingRegistry;

/**
 * @covers \Chubbyphp\Validation\Registry\ObjectMappingRegistry
 */
class ObjectMappingRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testWithKnownObjectMappings()
    {
        $objectMappings = [
            $this->getObjectMapping('Namespace\To\MyModel1'),
            $this->getObjectMapping('Namespace\To\MyModel2')
        ];

        $objectMappingRegistry = new ObjectMappingRegistry($objectMappings);

        self::assertSame($objectMappings[0], $objectMappingRegistry->getObjectMappingForClass('Namespace\To\MyModel1'));
        self::assertSame($objectMappings[1], $objectMappingRegistry->getObjectMappingForClass('Namespace\To\MyModel2'));
    }

    public function testWithUnknownObjectMappings()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('There is no mapping for class: Namespace\To\MyModel1');

        $objectMappingRegistry = new ObjectMappingRegistry([]);

        $objectMappingRegistry->getObjectMappingForClass('Namespace\To\MyModel1');
    }

    /**
     * @param string $class
     * @return ObjectMappingInterface
     */
    private function getObjectMapping(string $class): ObjectMappingInterface
    {
        /** @var ObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $objectMapping */
        $objectMapping = $this->getMockBuilder(ObjectMappingInterface::class)->getMockForAbstractClass();

        $objectMapping->expects(self::any())->method('getClass')->willReturn($class);

        return $objectMapping;
    }
}
