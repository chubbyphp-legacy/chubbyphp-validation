<?php

namespace Chubbyphp\Tests\Validation\Rules;

use Chubbyphp\Validation\Rules\AbstractLazyRequirementRule;

/**
 * @covers Chubbyphp\Validation\Rules\AbstractLazyRequirementRule
 */
final class AbstractLazyRequirementRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testSetRequirement()
    {
        $rule = $this->getRule();
        $rule->setRequirement('repository', new \stdClass());
    }

    public function testInvalidSetRequirement()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('There is no requirement with name unknown');

        $rule = $this->getRule();
        $rule->setRequirement('unknown', new \stdClass());
    }

    /**
     * @return AbstractLazyRequirementRule
     */
    private function getRule(): AbstractLazyRequirementRule
    {
        $rule = $this
            ->getMockBuilder(AbstractLazyRequirementRule::class)
            ->disableOriginalConstructor()
            ->setMethods(['required'])
            ->getMockForAbstractClass()
        ;

        $rule->expects(self::once())->method('requires')->willReturn(['repository']);

        $rule->repository = null;

        return $rule;
    }
}
