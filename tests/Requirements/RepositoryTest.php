<?php

namespace Chubbyphp\Tests\Validation\Requirements;

use Chubbyphp\Model\RepositoryInterface;
use Chubbyphp\Validation\Requirements\Repository;
use Chubbyphp\Validation\ValidatableModelInterface;

/**
 * @covers Chubbyphp\Validation\Requirements\Repository
 */
final class RepositoryTest extends \PHPUnit_Framework_TestCase
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

    public function testProvides()
    {
        $requirement = new Repository($this->getUserRepository());

        self::assertSame('repository', $requirement->provides());
    }

    public function testIsResponsibleWithInvalidValue()
    {
        $requirement = new Repository($this->getUserRepository());

        $model = new \stdClass();

        self::assertFalse($requirement->isResponsible($model));
    }

    public function testIsResponsibleWithValidArguments()
    {
        $requirement = new Repository($this->getUserRepository());

        $model = $this->getModel();

        self::assertTrue($requirement->isResponsible($model));
    }

    public function testGetRequirement()
    {
        $repository = $this->getUserRepository();
        $requirement = new Repository($repository);

        self::assertSame($repository, $requirement->getRequirement());
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
}
