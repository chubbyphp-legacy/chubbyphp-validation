<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Error;

use Chubbyphp\Validation\Error\ErrorInterface;
use Chubbyphp\Validation\Error\NestedErrorMessages;

/**
 * @covers \Chubbyphp\Validation\Error\NestedErrorMessages
 */
final class NestedErrorMessagesTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutMessages()
    {
        $errorMessages = new NestedErrorMessages([], function (string $key, array $arguments) { return $key; });

        self::assertEquals([], $errorMessages->getMessages());
    }

    public function testWithMessages()
    {
        $errors = [
            $this->getError('collection[_all]', 'constraint.collection.all'),
            $this->getError('collection[0].field1', 'constraint.collection0.constraint1'),
            $this->getError('collection[0].field1', 'constraint.collection0.constraint2'),
            $this->getError('collection[1].field1', 'constraint.collection1.constraint1'),
            $this->getError('collection[1].field1', 'constraint.collection1.constraint2'),
        ];

        $errorMessages = new NestedErrorMessages(
            $errors,
            function (string $key, array $arguments) { return $key; }
        );

        self::assertEquals([
            'collection' => [
                '_all' => [
                    'constraint.collection.all',
                ],
                0 => [
                    'field1' => [
                        'constraint.collection0.constraint1',
                        'constraint.collection0.constraint2',
                    ],
                ],
                1 => [
                    'field1' => [
                        'constraint.collection1.constraint1',
                        'constraint.collection1.constraint2',
                    ],
                ],
            ],
        ], $errorMessages->getMessages());
    }

    /**
     * @param string $path
     * @param string $key
     * @param array  $arguments
     *
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
