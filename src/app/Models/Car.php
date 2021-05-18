<?php

namespace FormulaTG\Models;

use FormulaTG\Utils\Helper;

class Car extends BaseModel
{
    private string $color;
    private string $equip;
    private string $pilotId;

    public function __construct(string $color, string $equip, int $pilotId, int $id = 0)
    {
        $this->id = $id;
        $this->color = $color;
        $this->equip = $equip;
        $this->pilotId = $pilotId;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getEquip(): string
    {
        return $this->equip;
    }

    public function getPilotId(): string
    {
        return $this->pilotId;
    }

    public static function getTableColumns(): array
    {
        return [
            'color', 
            'equip',
            'pilot_id',
        ];
    }

    public function getInsertValues(): array
    {
        return [
            'color' => $this->color,
            'equip' => $this->equip,
            'pilot_id' => $this->pilotId,
        ];
    }

    public static function populate(array $data): BaseModel
    {
        Helper::validatePopulate($data, self::getTableColumns(), 'Car');

        return new Car($data['color'], $data['equip'], $data['pilot_id'], $data['id']);
    }

    public function stringfy(): string
    {
        return "Car n°: {$this->id}, color: {$this->color}, equip: {$this->equip}";
    }
}
