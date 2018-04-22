<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\ValidationModel\Resources;

use Chubbyphp\Model\ModelInterface;

final class Model implements ModelInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    private function __construct()
    {
    }

    /**
     * @param string $id
     *
     * @return Model|ModelInterface
     */
    public static function create(string $id): ModelInterface
    {
        $model = new self();
        $model->id = $id;

        return $model;
    }

    /**
     * @param array $data
     *
     * @return Model|ModelInterface
     */
    public static function fromPersistence(array $data): ModelInterface
    {
        $model = new self();
        $model->id = $data['id'];
        $model->name = $data['name'];

        return $model;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function toPersistence(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
