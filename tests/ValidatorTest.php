<?php

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Translation\TranslatorInterface;
use Chubbyphp\Validation\Rules\UniqueModelRule;
use Chubbyphp\Validation\ValidatableModelInterface;
use Chubbyphp\Validation\RequirementInterface;
use Chubbyphp\Validation\Validator;
use Psr\Log\LoggerInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Rules\Email;
use Respect\Validation\Rules\NotEmpty;
use Respect\Validation\Validator as v;

/**
 * @covers Chubbyphp\Validation\Validator
 */
final class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateModelWhichGotNoValidators()
    {
        $logger = $this->getLogger();
        $translator = $this->getTranslator();

        $validator = new Validator([], $translator, $logger);

        $user = $this->getUser(['id' => 'id1', 'email' => 'firstname.lastname@domain.tld']);

        $errors = $validator->validateModel($user);

        self::assertSame([], $errors);

        self::assertCount(0, $translator->__translates);

        self::assertCount(0, $logger->__logs);
    }

    public function testValidateModelWhichGotAModelValidator()
    {
        $logger = $this->getLogger();
        $translator = $this->getTranslator();

        $validator = new Validator([
            $this->getRequirements(),
        ], $translator, $logger);

        $user = $this->getUser(
            [
                'id' => 'id1',
                'username' => 'user1',
                'email' => 'firstname.lastname@domain.tld',
            ],
            $respectModelValidator = $this->getRespectValidator([
                ['return' => true],
            ])->addRule($this->getUniqueModelRule()),
            [
                'email' => $respectEmailPropertyValidator = $this->getRespectValidator([
                    ['return' => true],
                ])->addRule($this->getEmail()),
            ]
        );

        $errors = $validator->validateModel($user);

        self::assertSame([], $errors);

        self::assertCount(0, $translator->__translates);

        self::assertCount(0, $logger->__logs);
    }

    public function testValidateModelWhichGotAModelValidatorWithException()
    {
        $logger = $this->getLogger();
        $translator = $this->getTranslator();

        $validator = new Validator([
            $this->getRequirements(),
        ], $translator, $logger);

        $user = $this->getUser(
            [
                'id' => 'id1',
                'username' => 'user1',
                'email' => 'firstname.lastname@domain.tld',
            ],
            $this->getRespectValidator([
                ['exception' => $this->getNestedValidationException([
                    $this->getValidationException('Unique model', ['properties' => ['username']]),
                ])],
            ])->addRule($this->getUniqueModelRule()),
            [
                'email' => $this->getRespectValidator([
                    ['exception' => $this->getNestedValidationException([
                        $this->getValidationException('Invalid email'),
                    ])],
                ])->addRule($this->getEmail()),
            ]
        );

        $errors = $validator->validateModel($user);

        self::assertSame(
            [
                'email' => ['Invalid email'],
                'username' => ['Unique model'],
            ],
            $errors
        );

        self::assertCount(2, $translator->__translates);

        self::assertSame('de', $translator->__translates[0]['locale']);
        self::assertSame('Invalid email', $translator->__translates[0]['key']);

        self::assertSame('de', $translator->__translates[1]['locale']);
        self::assertSame('Unique model', $translator->__translates[1]['key']);

        self::assertCount(2, $logger->__logs);
        self::assertSame('notice', $logger->__logs[0]['level']);
        self::assertSame('validation: field {field}, value {value}, message {message}', $logger->__logs[0]['message']);
        self::assertSame(
            ['field' => 'email', 'value' => 'firstname.lastname@domain.tld', 'message' => 'Invalid email'],
            $logger->__logs[0]['context']
        );
        self::assertSame('notice', $logger->__logs[1]['level']);
        self::assertSame('validation: field {field}, value {value}, message {message}', $logger->__logs[1]['message']);
        self::assertSame(
            ['field' => 'username', 'value' => '', 'message' => 'Unique model'],
            $logger->__logs[1]['context']
        );
    }

    public function testValidateModelWhichGotAModelValidatorWithoutGivenRequirement()
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Some message');

        $logger = $this->getLogger();
        $translator = $this->getTranslator();

        $validator = new Validator([], $translator, $logger);

        $user = $this->getUser(
            [
                'id' => 'id1',
                'username' => 'user1',
            ],
            $this->getRespectValidator([
                ['exception' => new \RuntimeException('Some message')],
            ])->addRule($this->getUniqueModelRule())
        );

        $errors = $validator->validateModel($user);

        self::assertSame([], $errors);

        self::assertCount(0, $translator->__translates);

        self::assertCount(0, $logger->__logs);
    }

    public function testValidateArray()
    {
        $logger = $this->getLogger();
        $translator = $this->getTranslator();

        $validator = new Validator([], $translator, $logger);

        $respectEmailValidator = $this->getRespectValidator([
            ['return' => true],
        ]);

        $respectEmailValidator->addRule($this->getEmail());

        $respectPasswordValidator = $this->getRespectValidator([
            ['return' => true],
        ]);

        $respectPasswordValidator->addRule($this->getNotEmpty());

        $errors = $validator->validateArray(
            ['email' => 'firstname.lastname@domain.tld', 'password' => 'password'],
            ['email' => $respectEmailValidator, 'password' => $respectPasswordValidator]
        );

        self::assertSame([], $errors);

        self::assertCount(0, $translator->__translates);

        self::assertCount(0, $logger->__logs);
    }

    /**
     * @param array  $properties
     * @param v|null $modelValidator
     * @param array  $fieldValidators
     *
     * @return ValidatableModelInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getUser(
        array $properties,
        v $modelValidator = null,
        array $fieldValidators = []
    ): ValidatableModelInterface {
        $user = $this
            ->getMockBuilder(ValidatableModelInterface::class)
            ->setMethods(['getId', 'getModelValidator', 'getPropertyValidators'])
            ->getMockForAbstractClass()
        ;

        foreach ($properties as $field => $value) {
            $user->$field = $value;
        }

        $user->expects(self::any())->method('getId')->willReturn($user->id);
        $user->expects(self::any())->method('getModelValidator')->willReturn($modelValidator);
        $user->expects(self::any())->method('getPropertyValidators')->willReturn($fieldValidators);

        return $user;
    }

    /**
     * @param bool $isResponsible
     *
     * @return RequirementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getRequirements(string $provides = 'repository', bool $isResponsible = true, $getRequirement = null): RequirementInterface
    {
        /** @var RequirementInterface|\PHPUnit_Framework_MockObject_MockObject $requirement */
        $requirement = $this
            ->getMockBuilder(RequirementInterface::class)
            ->setMethods(['provides', 'isResponsible', 'getRequirement'])
            ->getMockForAbstractClass();

        $requirement
            ->expects(self::any())
            ->method('provides')
            ->willReturn($provides);

        $requirement
            ->expects(self::any())
            ->method('isResponsible')
            ->willReturnCallback(function ($value) use ($isResponsible) {
                return $isResponsible;
            });

        $requirement
            ->expects(self::any())
            ->method('getRequirement')
            ->willReturn($getRequirement);

        return $requirement;
    }
    /**
     * @param array $assertStack
     *
     * @return v|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getRespectValidator(array $assertStack = []): v
    {
        $respectValidator = $this
            ->getMockBuilder(v::class)
            ->disableOriginalConstructor()
            ->setMethods(['addRule', 'getRules', 'assert'])
            ->getMockForAbstractClass();

        $respectValidator->__rules = [];
        $respectValidator
            ->expects(self::any())
            ->method('addRule')
            ->willReturnCallback(function (AbstractRule $rule) use ($respectValidator) {
                $respectValidator->__rules[] = $rule;

                return $respectValidator;
            })
        ;

        $respectValidator
            ->expects(self::any())
            ->method('getRules')
            ->willReturnCallback(function () use ($respectValidator) {
                return $respectValidator->__rules;
            })
        ;

        $assertCount = 0;
        $respectValidator
            ->expects(self::any())
            ->method('assert')
            ->willReturnCallback(function ($value) use (&$assertStack, &$assertCount) {
                $assert = array_shift($assertStack);

                self::assertNotNull(
                    $assert,
                    sprintf('There is no assert info within $assertStack at %d call.', $assertCount)
                );

                if (isset($assert['exception'])) {
                    throw $assert['exception'];
                }

                return $assert['return'];
            })
        ;

        return $respectValidator;
    }

    /**
     * @return UniqueModelRule|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getUniqueModelRule(): UniqueModelRule
    {
        $uniqueModelRule = $this
            ->getMockBuilder(UniqueModelRule::class)
            ->disableOriginalConstructor()
            ->setMethods(['requires'])
            ->getMock();

        $uniqueModelRule->expects(self::any())->method('requires')->willReturn(['repository']);

        return $uniqueModelRule;
    }

    /**
     * @return Email|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getEmail(): Email
    {
        $emailRule = $this
            ->getMockBuilder(Email::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $emailRule;
    }

    /**
     * @return NotEmpty|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getNotEmpty(): NotEmpty
    {
        $notEmptyRule = $this
            ->getMockBuilder(NotEmpty::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $notEmptyRule;
    }

    /**
     * @param ValidationException[]|array $childrenExceptions
     *
     * @return NestedValidationException|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getNestedValidationException(array $childrenExceptions): NestedValidationException
    {
        $nestedException = $this
            ->getMockBuilder(NestedValidationException::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIterator'])
            ->getMock();

        $nestedException
            ->expects(self::any())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($childrenExceptions))
        ;

        return $nestedException;
    }

    /**
     * @param string $mainMessage
     * @param array  $params
     *
     * @return ValidationException|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getValidationException(string $mainMessage, array $params = null)
    {
        $exception = $this
            ->getMockBuilder(ValidationException::class)
            ->disableOriginalConstructor()
            ->setMethods(['hasParam', 'getParam', 'setParam', 'getMainMessage'])
            ->getMock();

        $exception->__params = $params;

        $exception
            ->expects(self::any())
            ->method('hasParam')
            ->willReturnCallback(function (string $param) use ($exception) {
                return isset($exception->__params[$param]);
            })
        ;

        $exception
            ->expects(self::any())
            ->method('getParam')
            ->willReturnCallback(function (string $param) use ($exception) {
                return $exception->__params[$param];
            })
        ;

        $exception
            ->expects(self::any())
            ->method('setParam')
            ->willReturnCallback(function (string $param, $value) use ($exception) {
                $exception->__params[$param] = $value;
            })
        ;

        $exception
            ->expects(self::any())
            ->method('getMainMessage')
            ->willReturnCallback(function () use ($exception, $mainMessage) {
                if (isset($exception->__params['translator'])) {
                    $translator = $exception->__params['translator'];
                    $mainMessage = $translator($mainMessage);
                }

                return $mainMessage;
            })
        ;

        return $exception;
    }

    private function getTranslator(): TranslatorInterface
    {
        /** @var TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject $translator */
        $translator = $this
            ->getMockBuilder(TranslatorInterface::class)
            ->setMethods(['translate'])
            ->getMockForAbstractClass();

        $translator->__translates = [];

        $translator
            ->expects(self::any())
            ->method('translate')
            ->willReturnCallback(function (string $locale, string $key) use ($translator) {
                $translator->__translates[] = [
                    'locale' => $locale,
                    'key' => $key,
                ];

                return $key;
            })
        ;

        return $translator;
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        $methods = [
            'emergency',
            'alert',
            'critical',
            'error',
            'warning',
            'notice',
            'info',
            'debug',
        ];

        /** @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this
            ->getMockBuilder(LoggerInterface::class)
            ->setMethods(array_merge($methods, ['log']))
            ->getMockForAbstractClass()
        ;

        $logger->__logs = [];

        foreach ($methods as $method) {
            $logger
                ->expects(self::any())
                ->method($method)
                ->willReturnCallback(
                    function (string $message, array $context = []) use ($logger, $method) {
                        $logger->log($method, $message, $context);
                    }
                )
            ;
        }

        $logger
            ->expects(self::any())
            ->method('log')
            ->willReturnCallback(
                function (string $level, string $message, array $context = []) use ($logger) {
                    $logger->__logs[] = ['level' => $level, 'message' => $message, 'context' => $context];
                }
            )
        ;

        return $logger;
    }
}
