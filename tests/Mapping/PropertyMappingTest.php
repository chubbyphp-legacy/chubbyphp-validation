<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Validation\Constraint\ConstraintInterface;
use Chubbyphp\Validation\Mapping\PropertyMapping;

/**
 * @covers \Chubbyphp\Validation\Mapping\PropertyMapping
 */
class PropertyMappingTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $name = 'property1';
        $constraints = [
            $this->getConstraint(),
            $this->getConstraint(),
        ];

        $propertyMapping = new PropertyMapping($name, $constraints);

        self::assertSame($name, $propertyMapping->getName());
        self::assertSame($constraints, $propertyMapping->getConstraints());
    }

    /**
     * @return ConstraintInterface
     */
    private function getConstraint(): ConstraintInterface
    {
        return $this->getMockBuilder(ConstraintInterface::class)->getMockForAbstractClass();
    }
}
