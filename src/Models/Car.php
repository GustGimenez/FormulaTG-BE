<?php

namespace FormulaTG\Models;

use Exception;

class Car extends BaseModel
{
    private string $color;
    private string $equip;
    private string $driverName;

    public function __construct(string $color, string $equip, string $driverName, int $id = 0)
    {
        $this->id = $id;
        $this->color = $color;
        $this->equip = $equip;
        $this->driverName = $driverName;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getEquip(): string
    {
        return $this->equip;
    }

    public function getDriverName(): string
    {
        return $this->driverName;
    }

    public static function getTableColumns(): array
    {
        return [
            'color', 
            'equip',
            'driver_name',
        ];
    }

    public function getInsertValues(): array
    {
        return [
            'color' => $this->color,
            'equip' => $this->equip,
            'driver_name' => $this->driverName,
        ];
    }

    protected static function validatePopulate(array $data): void 
    {
        $expectedValues = self::getTableColumns();
        $expectedValue[] = 'id';

        foreach ($expectedValues as $expectedValue) {
            if (!array_key_exists($expectedValue, $data)) {
                throw new Exception("The key $expectedValue is expected to create a new car");
            }
        }
    }

    public static function populate(array $data): BaseModel
    {
        self::validatePopulate($data);

        return new Car($data['color'], $data['equip'], $data['driver_name'], $data['id']);
    }

    public function stringfy(): string
    {
        return "Car n°: {$this->id}, color: {$this->color}, equip: {$this->equip}, driver: {$this->driverName}";
    }
}
