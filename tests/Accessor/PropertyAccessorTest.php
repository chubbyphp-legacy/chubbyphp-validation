<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Accessor;

use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\ValidatorLogicException;
use Doctrine\Common\Persistence\Proxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Accessor\PropertyAccessor
 */
class PropertyAccessorTest extends TestCase
{
    public function testGetValue()
    {
        $object = new Model();

        $object->setName('Name');

        $accessor = new PropertyAccessor('name');

        self::assertSame('Name', $accessor->getValue($object));
    }

    public function testGetValueCanAccessPrivatePropertyThroughDoctrineProxyClass()
    {
        $object = new class() extends Model implements Proxy {
            public function __load()
            {
            }

            /**
             * @return bool
             */
            public function __isInitialized()
            {
                return false;
            }
        };

        $object->setName('Name');

        $accessor = new PropertyAccessor('name');

        self::assertSame('Name', $accessor->getValue($object));
    }

    public function testMissingGet()
    {
        self::expectException(ValidatorLogicException::class);

        $object = new class() {
        };

        $accessor = new PropertyAccessor('name');
        $accessor->getValue($object);
    }
}

class Model
{
    /**
     * @var string
     */
    protected $name;

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
