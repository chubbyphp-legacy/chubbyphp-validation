<?php

namespace Chubbyphp\Tests\Validation;

use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Validation\Rules\UniqueModelRule;
use Chubbyphp\Validation\ValidatableModelInterface;
use Chubbyphp\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Rules\Email;
use Respect\Validation\Rules\NotEmpty;
use Respect\Validation\Validator as RespectValidator;

/**
 * @covers Chubbyphp\Validation\Validator
 */
final class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $eval = <<<'EOT'
namespace Chubbyphp\Validation
{
    function get_class($object)
    {
        $class = \get_class($object);
        
        $map = [
            'ValidatableModelInterface' => ValidatableModelInterface::class
        ];

        // mocked class
        if ('Mock_' === \substr($class, 0, 5)) {
            $classParts = \explode('_', $class);
            $className = $classParts[1];
            
            if (isset($map[$className])) {
                return $map[$className];
            }
        }
    
        return $class;
    }
}
EOT;
        eval($eval);
    }

    public function testValidateModelWhichGotNoValidators()
    {
        $validator = new Validator();

        $user = $this->getUser(['id' => 'id1', 'email' => 'firstname.lastname@domain.tld']);

        $errors = $validator->validateModel($user);

        self::assertSame([], $errors);
    }

    public function testValidateModelWhichGotAModelValidator()
    {
        $validator = new Validator([
            $this->getUserRepository(),
        ]);

        $respectValidator = $this->getRespectValidator([
            ['return' => true],
        ]);
        $respectValidator->addRule($this->getUniqueModelRule());

        $user = $this->getUser(
            [
                'id' => 'id1',
                'email' => 'firstname.lastname@domain.tld',
            ],
            $respectValidator
        );

        $errors = $validator->validateModel($user);

        self::assertSame([], $errors);
    }

    public function testValidateModelWhichGotAModelValidatorWithException()
    {
        $validator = new Validator([
            $this->getUserRepository(),
        ]);

        $nestedException = $this->getNestedValidationException([
            $this->getValidationException(['properties' => ['email']], 'Unique Model'),
            $this->getValidationException([], 'Something else is weird'),
        ]);

        $respectValidator = $this->getRespectValidator([
            ['exception' => $nestedException],
        ]);
        $respectValidator->addRule($this->getUniqueModelRule());

        $user = $this->getUser(
            [
                'id' => 'id1',
                'email' => 'firstname.lastname@domain.tld',
            ],
            $respectValidator
        );

        $errors = $validator->validateModel($user);

        self::assertSame(
            [
                'email' => ['Unique Model'],
                '__model' => ['Something else is weird'],
            ],
            $errors
        );
    }

    public function testValidateModelWhichGotAPropertyValidators()
    {
        $validator = new Validator();

        $respectEmailValidator = $this->getRespectValidator([
            ['return' => true],
        ]);
        $respectEmailValidator->addRule(new NotEmpty())->addRule($this->getEmail());

        $respectPasswordValidator = $this->getRespectValidator([
            ['return' => true],
        ]);
        $respectPasswordValidator->addRule($this->getNotEmpty());

        $user = $this->getUser(
            [
                'id' => 'id1',
                'email' => 'firstname.lastname@domain.tld',
                'password' => 'password',
            ],
            null,
            ['email' => $respectEmailValidator, 'password' => $respectPasswordValidator]
        );

        $errors = $validator->validateModel($user);

        self::assertSame([], $errors);
    }

    public function testValidateModelWhichGotAPropertyValidatorsWithException()
    {
        $validator = new Validator();

        $nestedEmailException = $this->getNestedValidationException([
            $this->getValidationException([], 'Empty email'),
            $this->getValidationException([], 'Invalid E-Mail Address'),
        ]);

        $respectEmailValidator = $this->getRespectValidator([
            ['exception' => $nestedEmailException],
        ]);
        $respectEmailValidator->addRule(new NotEmpty())->addRule($this->getEmail());

        $nestedPasswordException = $this->getNestedValidationException([
            $this->getValidationException([], 'Empty password'),
        ]);

        $respectPasswordValidator = $this->getRespectValidator([
            ['exception' => $nestedPasswordException],
        ]);
        $respectPasswordValidator->addRule($this->getNotEmpty());

        $user = $this->getUser(
            [
                'id' => 'id1',
                'email' => '',
                'password' => '',
            ],
            null,
            ['email' => $respectEmailValidator, 'password' => $respectPasswordValidator]
        );

        $errors = $validator->validateModel($user);

        self::assertSame(
            [
                'email' => ['Empty email', 'Invalid E-Mail Address'],
                'password' => ['Empty password'],
            ],
            $errors
        );
    }

    public function testValidateArray()
    {
        $validator = new Validator();

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
    }

    public function testValidateInvalidArray()
    {
        $validator = new Validator();

        $nestedEmailException = $this->getNestedValidationException([
            $this->getValidationException([], 'Empty email'),
            $this->getValidationException([], 'Invalid E-Mail Address'),
        ]);

        $respectEmailValidator = $this->getRespectValidator([
            ['exception' => $nestedEmailException],
        ]);
        $respectEmailValidator->addRule($this->getEmail());

        $nestedPasswordException = $this->getNestedValidationException([
            $this->getValidationException([], 'Empty password'),
        ]);

        $respectPasswordValidator = $this->getRespectValidator([
            ['exception' => $nestedPasswordException],
        ]);
        $respectPasswordValidator->addRule($this->getNotEmpty());

        $errors = $validator->validateArray(
            ['email' => '', 'password' => ''],
            ['email' => $respectEmailValidator, 'password' => $respectPasswordValidator]
        );

        self::assertSame(
            [
                'email' => ['Empty email', 'Invalid E-Mail Address'],
                'password' => ['Empty password'],
            ],
            $errors
        );
    }

    /**
     * @param array                 $properties
     * @param RespectValidator|null $modelValidator
     * @param array                 $propertyValidators
     *
     * @return ValidatableModelInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getUser(
        array $properties,
        RespectValidator $modelValidator = null,
        array $propertyValidators = []
    ): ValidatableModelInterface {
        $user = $this
            ->getMockBuilder(ValidatableModelInterface::class)
            ->setMethods(['getId', 'getModelValidator', 'getPropertyValidators'])
            ->getMockForAbstractClass()
        ;

        foreach ($properties as $property => $value) {
            $user->$property = $value;
        }

        $user->expects(self::any())->method('getId')->willReturn($user->id);
        $user->expects(self::any())->method('getModelValidator')->willReturn($modelValidator);
        $user->expects(self::any())->method('getPropertyValidators')->willReturn($propertyValidators);

        return $user;
    }

    /**
     * @param string $class
     *
     * @return RepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getUserRepository($class = ValidatableModelInterface::class): RepositoryInterface
    {
        $userRepository = $this
            ->getMockBuilder(RepositoryInterface::class)
            ->setMethods(['getModelClass'])
            ->getMockForAbstractClass();

        $userRepository->expects(self::any())->method('getModelClass')->willReturn($class);

        return $userRepository;
    }
    /**
     * @return NestedValidationException|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getRespectValidator(array $assertStack = []): RespectValidator
    {
        $respectValidator = $this
            ->getMockBuilder(RespectValidator::class)
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
            ->getMock();

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
     * @return NestedValidationException|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getNestedValidationException(array $childrenExceptions): NestedValidationException
    {
        $nestedException = $this
            ->getMockBuilder(NestedValidationException::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIterator', 'getMessages'])
            ->getMock();

        $nestedException
            ->expects(self::any())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator($childrenExceptions))
        ;

        $nestedException
            ->expects(self::any())
            ->method('getMessages')
            ->willReturnCallback(function () use ($childrenExceptions) {
                $messages = [];
                foreach ($childrenExceptions as $childrenException) {
                    $messages[] = $childrenException->getMainMessage();
                }

                return $messages;
            })
        ;

        return $nestedException;
    }

    /**
     * @param array $params
     *
     * @return ValidationException|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getValidationException(array $params, string $mainMessage)
    {
        $exception = $this
            ->getMockBuilder(ValidationException::class)
            ->disableOriginalConstructor()
            ->setMethods(['hasParam', 'getParam', 'getMainMessage'])
            ->getMock();

        $exception
            ->expects(self::any())
            ->method('hasParam')
            ->willReturnCallback(function (string $param) use ($params) {
                return isset($params[$param]);
            })
        ;

        $exception
            ->expects(self::any())
            ->method('getParam')
            ->willReturnCallback(function (string $param) use ($params) {
                return $params[$param];
            })
        ;

        $exception
            ->expects(self::any())
            ->method('getMainMessage')
            ->willReturn($mainMessage)
        ;

        return $exception;
    }
}
