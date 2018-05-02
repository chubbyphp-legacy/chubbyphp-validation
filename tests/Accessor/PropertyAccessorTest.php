<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Accessor;

use Chubbyphp\Validation\Accessor\PropertyAccessor;
use Chubbyphp\Validation\ValidatorLogicException;
use Chubbyphp\Tests\Validation\Resources\Model\AbstractManyModel;
use Doctrine\Common\Persistence\Proxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Accessor\PropertyAccessor
 */
class PropertyAccessorTest extends TestCase
{
    public function testSetValue()
    {
        $object = new class() {
            /**
             * @var string
             */
            private $name;

            /**
             * @return string
             */
            public function getName(): string
            {
                return $this->name;
            }
        };

        $accessor = new PropertyAccessor('name');
        $accessor->setValue($object, 'Name');

        self::assertSame('Name', $object->getName());
    }

    public function testSetValueCanAccessPrivatePropertyThroughDoctrineProxyClass()
    {
        $object = new class() extends AbstractManyModel implements Proxy {
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

        $accessor = new PropertyAccessor('address');

        $accessor->setValue($object, 'Address');

        self::assertSame('Address', $accessor->getValue($object));
    }

    public function testMissingSet()
    {
        self::expectException(ValidatorLogicException::class);

        $object = new class() {
        };

        $accessor = new PropertyAccessor('name');
        $accessor->setValue($object, 'Name');
    }

    public function testGetValue()
    {
        $object = new class() {
            /**
             * @var string
             */
            private $name;

            /**
             * @param string $name
             */
            public function setName(string $name)
            {
                $this->name = $name;
            }
        };

        $object->setName('Name');

        $accessor = new PropertyAccessor('name');

        self::assertSame('Name', $accessor->getValue($object));
    }

    public function testGetValueCanAccessPrivatePropertyThroughDoctrineProxyClass()
    {
        $object = new class() extends AbstractManyModel implements Proxy {
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

        $object->setAddress('Address');

        $accessor = new PropertyAccessor('address');

        self::assertSame('Address', $accessor->getValue($object));
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
