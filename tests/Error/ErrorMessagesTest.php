<?php

namespace Chubbyphp\Tests\Validation\Error;

use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Error\ErrorMessages;

/**
 * @covers \Chubbyphp\Validation\Error\ErrorMessages
 */
class ErrorMessagesTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutMessages()
    {
        $errorMessages = new ErrorMessages([], function (string $key, array $arguments) { return $key; });

        self::assertEquals([], $errorMessages->getMessages());
    }

    public function testWithMessages()
    {
        $errorOne = $this->getError('somepath', 'constraint.myconstraint.error');
        $errorTwo = $this->getError('somepath', 'constraint.myconstraint.yetanothererror');
        $errorThree = $this->getError('anotherpath', 'constraint.myconstraint.yetyetanothererror');

        $errorMessages = new ErrorMessages(
            [$errorOne, $errorTwo, $errorThree],
            function (string $key, array $arguments) { return $key; }
        );

        self::assertEquals([
            'somepath' => [
                'constraint.myconstraint.error',
                'constraint.myconstraint.yetanothererror'
            ],
            'anotherpath' => [
                'constraint.myconstraint.yetyetanothererror'
            ]
        ], $errorMessages->getMessages());
    }

    /**
     * @param string $path
     * @param string $key
     * @param array $arguments
     * @return ErrorInterface
     */
    private function getError(string $path, string $key, array $arguments = []): ErrorInterface
    {
        /** @var ErrorInterface|\PHPUnit_Framework_MockObject_MockObject $error */
        $error = $this->getMockBuilder(ErrorInterface::class)->getMockForAbstractClass();

        $error->expects(self::any())->method('getPath')->willReturn($path);
        $error->expects(self::any())->method('getKey')->willReturn($key);
        $error->expects(self::any())->method('getArguments')->willReturn($arguments);

        return $error;
    }
}
