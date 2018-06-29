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
        $mock = $this->getMockBuilder($interface)->setMethods(array_keys($methods))->getMockForAbstractClass();

        $methodMock = function ($method, $calls) use ($mock) {
            $mock->expects(self::exactly(count($calls)))
                ->method($method)
                ->willReturnCallback(function () use (&$calls) {
                    $call = array_shift($calls);

                    if (!$call instanceof Call) {
                        $callObject = Call::create();
                        if (isset($call['arguments'])) {
                            $callObject->setArguments($call['arguments']);
                        }

                        if (isset($call['exception'])) {
                            $callObject->setException($call['exception']);
                        }

                        if (array_key_exists('return', $call)) {
                            $callObject->setReturn($call['return']);
                        }

                        $call = $callObject;
                    }

                    if ($call->hasArguments()) {
                        $arguments = func_get_args();
                        foreach ($call->getArguments() as $i => $argument) {
                            self::assertSame($argument, $arguments[$i]);
                        }
                    }

                    if ($call->hasException()) {
                        throw $call->getException();
                    }

                    if ($call->hasReturn()) {
                        return $call->getReturn();
                    }
                });
        };

        foreach ($methods as $method => $calls) {
            $methodMock($method, $calls);
        }

        return $mock;
    }
}

class Call
{
    /**
     * @var bool
     */
    private $hasArguments = false;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var bool
     */
    private $hasException = false;

    /**
     * @var \Exception|null
     */
    private $exception;

    /**
     * @var bool
     */
    private $hasReturn = false;

    /**
     * @var mixed
     */
    private $return;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * @param array $arguments
     *
     * @return self
     */
    public function setArguments(array $arguments): self
    {
        $this->hasArguments = true;
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param \Exception|null $exception
     *
     * @return self
     */
    public function setException(\Exception $exception): self
    {
        $this->hasException = true;
        $this->exception = $exception;

        return $this;
    }

    /**
     * @param mixed $return
     *
     * @return self
     */
    public function setReturn($return)
    {
        $this->hasReturn = true;
        $this->return = $return;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasArguments(): bool
    {
        return $this->hasArguments;
    }

    /**
     * @return bool
     */
    public function hasException(): bool
    {
        return $this->hasException;
    }

    /**
     * @return bool
     */
    public function hasReturn(): bool
    {
        return $this->hasReturn;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return \Exception|null
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }
}
