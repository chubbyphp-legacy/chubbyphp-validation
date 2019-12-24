<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Error;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Error\ApiProblemErrorMessages;
use Chubbyphp\Validation\Error\ErrorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Error\ApiProblemErrorMessages
 *
 * @internal
 */
final class ApiProblemErrorMessagesTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithoutMessages(): void
    {
        $errorMessages = new ApiProblemErrorMessages([]);

        self::assertEquals([], $errorMessages->getMessages());
    }

    public function testWithMessages(): void
    {
        $errors = [
            $this->getError('collection[_all]', 'constraint.collection.all'),
            $this->getError('collection[0].field1', 'constraint.collection0.constraint1', ['key' => 'value']),
            $this->getError('collection[0].field1', 'constraint.collection0.constraint2'),
            $this->getError('collection[1].field1', 'constraint.collection1.constraint1'),
            $this->getError('collection[1].field1', 'constraint.collection1.constraint2'),
        ];

        $errorMessages = new ApiProblemErrorMessages($errors);

        self::assertEquals([
            [
                'name' => 'collection[_all]',
                'reason' => 'constraint.collection.all',
                'details' => [],
            ],
            [
                'name' => 'collection[0].field1',
                'reason' => 'constraint.collection0.constraint1',
                'details' => ['key' => 'value'],
            ],
            [
                'name' => 'collection[0].field1',
                'reason' => 'constraint.collection0.constraint2',
                'details' => [],
            ],
            [
                'name' => 'collection[1].field1',
                'reason' => 'constraint.collection1.constraint1',
                'details' => [],
            ],
            [
                'name' => 'collection[1].field1',
                'reason' => 'constraint.collection1.constraint2',
                'details' => [],
            ],
        ], $errorMessages->getMessages());
    }

    private function getError(string $path, string $key, array $arguments = []): ErrorInterface
    {
        /* @var ErrorInterface|MockObject $error */
        return $this->getMockByCalls(ErrorInterface::class, [
            Call::create('getPath')->with()->willReturn($path),
            Call::create('getKey')->with()->willReturn($key),
            Call::create('getArguments')->with()->willReturn($arguments),
        ]);
    }
}
