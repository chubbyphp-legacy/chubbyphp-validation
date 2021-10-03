<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Unit\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\EmailConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\EmailConstraint
 *
 * @internal
 */
final class EmailConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue(): void
    {
        $constraint = new EmailConstraint();

        self::assertEquals([], $constraint->validate('email', null, $this->getContext()));
    }

    public function testWithBlankValue(): void
    {
        $constraint = new EmailConstraint();

        self::assertEquals([], $constraint->validate('email', '', $this->getContext()));
    }

    public function testInvalidType(): void
    {
        $constraint = new EmailConstraint();

        $error = new Error('email', 'constraint.email.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('email', [], $this->getContext()));
    }

    public function testWithEmail(): void
    {
        $constraint = new EmailConstraint();

        self::assertEquals([], $constraint->validate('email', 'firstname.lastname@domain.tld', $this->getContext()));
    }

    public function testPattern(): void
    {
        $matches = [];
        preg_match(EmailConstraint::PATTERN, 'firstname.lastname@domain.tld', $matches);

        self::assertSame('firstname.lastname', $matches[1]);
        self::assertSame('domain.tld', $matches[2]);
    }

    public function testWithInvalidEmail(): void
    {
        $constraint = new EmailConstraint();

        $error = new Error('email', 'constraint.email.invalidformat', ['value' => 'name']);

        self::assertEquals([$error], $constraint->validate('email', 'name', $this->getContext()));
    }

    private function getContext(): ValidatorContextInterface
    {
        // @var ValidatorContextInterface|MockObject $context
        return $this->getMockByCalls(ValidatorContextInterface::class);
    }
}
