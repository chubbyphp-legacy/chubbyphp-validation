<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation;

use PHPUnit\Framework\MockObject\MockObject;

trait MockForInterfaceTrait
{
    /**
     * @param string $interface
     * @param array  $methods
     *
     * @return MockObject
     */
    private function getMockForInterface(string $interface, array $methods = []): MockObject
    {
        /** @var MockObject $mock */
        $mock = $this->getMockBuilder($interface)->getMockForAbstractClass();

        $methodMock = function ($method, $calls) use ($mock) {
            $mock->expects(self::exactly(count($calls)))
                ->method($method)
                ->willReturnCallback(function () use (&$calls) {
                    $call = array_shift($calls);

                    if (isset($call['arguments'])) {
                        $arguments = func_get_args();
                        foreach ($call['arguments'] as $i => $argument) {
                            self::assertSame($argument, $arguments[$i]);
                        }
                    }

                    if (isset($call['exception'])) {
                        throw $call['exception'];
                    }

                    if (array_key_exists('return', $call)) {
                        return $call['return'];
                    }

                    return null;
                });
        };

        foreach ($methods as $method => $calls) {
            $methodMock($method, $calls);
        }

        return $mock;
    }
}
