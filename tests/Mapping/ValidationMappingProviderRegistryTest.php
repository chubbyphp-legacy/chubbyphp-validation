<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Mapping;

use Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry;
use Chubbyphp\Validation\ValidatorLogicException;
use Chubbyphp\Validation\Mapping\ValidationMappingProviderInterface;
use Doctrine\Common\Persistence\Proxy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Mapping\ValidationMappingProviderRegistry
 */
class ValidationMappingProviderRegistryTest extends TestCase
{
    public function testGetObjectMapping()
    {
        $object = $this->getObject();

        $registry = new ValidationMappingProviderRegistry([
            $this->getValidationObjectMapping(),
        ]);

        $mapping = $registry->provideMapping(get_class($object));

        self::assertInstanceOf(ValidationMappingProviderInterface::class, $mapping);
    }

    public function testGetMissingObjectMapping()
    {
        self::expectException(ValidatorLogicException::class);
        self::expectExceptionMessage('There is no mapping for class: "stdClass"');

        $registry = new ValidationMappingProviderRegistry([]);

        $registry->provideMapping(get_class(new \stdClass()));
    }

    public function testGetObjectMappingFromDoctrineProxy()
    {
        $object = $this->getProxyObject();

        $registry = new ValidationMappingProviderRegistry([
            $this->getValidationProxyObjectMapping(),
        ]);

        $mapping = $registry->provideMapping(get_class($object));

        self::assertInstanceOf(ValidationMappingProviderInterface::class, $mapping);
    }

    /**
     * @return ValidationMappingProviderInterface
     */
    private function getValidationObjectMapping(): ValidationMappingProviderInterface
    {
        /** @var ValidationMappingProviderInterface|MockObject $objectMapping */
        $objectMapping = $this->getMockBuilder(ValidationMappingProviderInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $object = $this->getObject();

        $objectMapping->expects(self::any())->method('getClass')->willReturnCallback(
            function () use ($object) {
                return get_class($object);
            }
        );

        return $objectMapping;
    }

    /**
     * @return ValidationMappingProviderInterface
     */
    private function getValidationProxyObjectMapping(): ValidationMappingProviderInterface
    {
        /** @var ValidationMappingProviderInterface|MockObject $objectMapping */
        $objectMapping = $this->getMockBuilder(ValidationMappingProviderInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $object = $this->getProxyObject();

        $objectMapping->expects(self::any())->method('getClass')->willReturnCallback(
            function () use ($object) {
                return AbstractManyModel::class;
            }
        );

        return $objectMapping;
    }

    /**
     * @return object
     */
    private function getObject()
    {
        return new class() {
            /**
             * @var string
             */
            private $name;

            /**
             * @return string|null
             */
            public function getName()
            {
                return $this->name;
            }

            /**
             * @param string $name
             *
             * @return self
             */
            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }
        };
    }

    /**
     * @return object
     */
    private function getProxyObject()
    {
        return new class() extends AbstractManyModel implements Proxy {
            /**
             * Initializes this proxy if its not yet initialized.
             *
             * Acts as a no-op if already initialized.
             */
            public function __load()
            {
            }

            /**
             * Returns whether this proxy is initialized or not.
             *
             * @return bool
             */
            public function __isInitialized()
            {
            }
        };
    }
}

abstract class AbstractManyModel
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
