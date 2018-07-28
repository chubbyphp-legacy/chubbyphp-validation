<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Constraint;

use Chubbyphp\Mock\MockByCallsTrait;
use Chubbyphp\Validation\Constraint\EmailConstraint;
use Chubbyphp\Validation\Error\Error;
use Chubbyphp\Validation\ValidatorContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Validation\Constraint\EmailConstraint
 */
final class EmailConstraintTest extends TestCase
{
    use MockByCallsTrait;

    public function testWithNullValue()
    {
        $constraint = new EmailConstraint();

        self::assertEquals([], $constraint->validate('email', null, $this->getContext()));
    }

    public function testWithBlankValue()
    {
        $constraint = new EmailConstraint();

        self::assertEquals([], $constraint->validate('email', '', $this->getContext()));
    }

    public function testInvalidType()
    {
        $constraint = new EmailConstraint();

        $error = new Error('email', 'constraint.email.invalidtype', ['type' => 'array']);

        self::assertEquals([$error], $constraint->validate('email', [], $this->getContext()));
    }

    public function testWithEmail()
    {
        $constraint = new EmailConstraint();

        self::assertEquals([], $constraint->validate('email', 'firstname.lastname@domain.tld', $this->getContext()));
    }

    public function testPattern()
    {
        $matches = [];
        preg_match(EmailConstraint::PATTERN, 'firstname.lastname@domain.tld', $matches);

        self::assertSame('firstname.lastname', $matches[1]);
        self::assertSame('domain.tld', $matches[2]);
    }

    public function testWithInvalidEmail()
    {
        $constraint = new EmailConstraint();

        $error = new Error('email', 'constraint.email.invalidformat', ['value' => 'name']);

        self::assertEquals([$error], $constraint->validate('email', 'name', $this->getContext()));
    }

    /**
     * @return ValidatorContextInterface
     */
    private function getContext(): ValidatorContextInterface
    {
        /** @var ValidatorContextInterface|MockObject $context */
        $context = $this->getMockByCalls(ValidatorContextInterface::class);

        return $context;
    }
}
