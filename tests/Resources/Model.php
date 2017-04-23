<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Validation\Resources;

final class Model
{
    /**
     * @var string|null
     */
    private $notNull;

    /**
     * @var string|null
     */
    private $notBlank;

    /**
     * @return null|string
     */
    public function getNotNull()
    {
        return $this->notNull;
    }

    /**
     * @param null|string $notNull
     */
    public function setNotNull($notNull)
    {
        $this->notNull = $notNull;
    }

    /**
     * @return null|string
     */
    public function getNotBlank()
    {
        return $this->notBlank;
    }

    /**
     * @param null|string $notBlank
     */
    public function setNotBlank($notBlank)
    {
        $this->notBlank = $notBlank;
    }
}
