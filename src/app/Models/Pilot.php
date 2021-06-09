<?php

namespace FormulaTG\Models;

use FormulaTG\Utils\Helper;

class Pilot extends BaseModel
{
    private string $name;
    private int $age;

    public function __construct(string $name, int $age, int $id = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
    }

    public static function getTableColumns(): array
    {
        return ['name', 'age'];
    }

    public function getInsertValues(): array
    {
        return [
            'name' => $this->name,
            'age' => $this->age,
        ];
    }

    public static function populate(array $data): BaseModel
    {
        Helper::validatePopulate($data, self::getTableColumns(), 'Pilot');

        return new Pilot($data['name'], $data['age'], $data['id']);
    }

    public function stringfy(): string
    {
        return "Pilot n°: {$this->id}, name: {$this->name}, age: {$this->age}";
    }
}
