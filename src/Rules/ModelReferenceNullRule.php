<?php

declare(strict_types=1);

namespace Chubbyphp\Validation\Rules;

use Chubbyphp\Model\Reference\ModelReferenceInterface;
use Respect\Validation\Rules\AbstractRule;

class ModelReferenceNullRule extends AbstractRule
{
    /**
     * @param ModelReferenceInterface $modelReference
     * @return bool
     */
    public function validate($modelReference)
    {
        $this->validateInputType($modelReference);

        return $modelReference->getModel() === null;
    }

    /**
     * @param ModelReferenceInterface $modelReference
     *
     * @throws \InvalidArgumentException
     */
    private function validateInputType($modelReference)
    {
        if (!$modelReference instanceof ModelReferenceInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The to validate value needs to be an instance of %s, %s given!',
                    ModelReferenceInterface::class,
                    get_class($modelReference)
                )
            );
        }
    }
}
