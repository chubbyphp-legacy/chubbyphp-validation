<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ValidationDoctrine\Resources;

class Model
{
    /**
     * @var string
     */
    private $idPart1;

    /**
     * @var string
     */
    private $idPart2;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @param string $idPart1
     * @param string $idPart2
     */
    public function __construct(string $idPart1, string $idPart2)
    {
        $this->idPart1 = $idPart1;
        $this->idPart2 = $idPart2;
    }

    /**
     * @return string
     */
    public function getIdPart1(): string
    {
        return $this->idPart1;
    }

    /**
     * @return string
     */
    public function getIdPart2(): string
    {
        return $this->idPart2;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
