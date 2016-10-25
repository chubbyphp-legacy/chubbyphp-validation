<?php

namespace Chubbyphp\Tests\Validation\Requirements;

use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Validation\Requirements\RepositoryRequirement;
use Chubbyphp\Validation\Rules\UniqueModelRule;
use Chubbyphp\Validation\ValidatableModelInterface;
use Respect\Validation\Rules\NotEmpty;

/**
 * @covers Chubbyphp\Validation\Requirements\RepositoryRequirements
 */
final class RepositoryHelperTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $eval = <<<'EOT'
namespace Chubbyphp\Validation\Requirements
{
    use Chubbyphp\Validation\ValidatableModelInterface;

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

    public function testIsResponsibleWithInvalidValue()
    {
        $helper = new RepositoryRequirement($this->getUserRepository());

        $rule = $this->getUniqueModelRule();
        $model = new \stdClass();

        self::assertFalse($helper->isResponsible($rule, $model));
    }

    public function testIsResponsibleWithValidArguments()
    {
        $helper = new RepositoryRequirement($this->getUserRepository());

        $rule = $this->getUniqueModelRule();
        $model = $this->getModel();

        self::assertTrue($helper->isResponsible($rule, $model));
    }

    public function testHelp()
    {
        $helper = new RepositoryRequirement($this->getUserRepository());

        $rule = $this->getUniqueModelRule(1);
        $model = $this->getModel();

        $helper->apply($rule, $model);
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
     * @return ValidatableModelInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getModel(): ValidatableModelInterface
    {
        $model = $this
            ->getMockBuilder(ValidatableModelInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $model;
    }

    /**
     * @param int $setRepositoryCallCount
     *
     * @return UniqueModelRule|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getUniqueModelRule($setRepositoryCallCount = 0): UniqueModelRule
    {
        $uniqueModelRule = $this
            ->getMockBuilder(UniqueModelRule::class)
            ->disableOriginalConstructor()
            ->setMethods(['setRepository'])
            ->getMock();

        $uniqueModelRule
            ->expects(self::exactly($setRepositoryCallCount))
            ->method('setRepository')
            ->willReturnCallback(function (RepositoryInterface $repository) {
            });

        return $uniqueModelRule;
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
}
