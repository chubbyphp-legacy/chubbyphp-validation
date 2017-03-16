<?php

namespace Chubbyphp\Tests\Validation\Rules;

use Chubbyphp\Model\ModelInterface;
use Chubbyphp\Model\Reference\ModelReferenceInterface;
use Chubbyphp\Validation\Rules\ModelReferenceNullRule;
use Respect\Validation\Rules\RuleTestCase;

/**
 * @covers Chubbyphp\Validation\Rules\ModelReferenceNullRule
 */
final class ModelReferenceNullRuleTest extends RuleTestCase
{
    public function testValidateWithInvalidInput()
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage(
            'The to validate value needs to be an instance of '.ModelReferenceInterface::class
            .', stdClass given!'
        );

        $uniqueModel = new ModelReferenceNullRule();

        $uniqueModel->validate(new \stdClass);
    }

    public function providerForInvalidInput()
    {
        $modelReference1 = $this->getModelReference($this->getModel());

        $uniqueModel1 = new ModelReferenceNullRule();

        return [
            [$uniqueModel1, $modelReference1],
        ];
    }

    public function providerForValidInput()
    {
        $modelReference1 = $this->getModelReference();

        $uniqueModel1 = new ModelReferenceNullRule();

        return [
            [$uniqueModel1, $modelReference1],
        ];
    }

    /**
     * @param ModelInterface|null $model
     *
     * @return ModelReferenceInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getModelReference(ModelInterface $model = null)
    {
        $modelReference = $this
            ->getMockBuilder(ModelReferenceInterface::class)
            ->setMethods(['getModel'])
            ->getMockForAbstractClass()
        ;

        $modelReference->model = $model;

        $modelReference->expects(self::any())->method('getModel')->willReturn($modelReference->model);

        return $modelReference;
    }

    /**
     * @return ModelInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getModel()
    {
        $model = $this
            ->getMockBuilder(ModelInterface::class)
            ->getMockForAbstractClass()
        ;

        return $model;
    }
}
