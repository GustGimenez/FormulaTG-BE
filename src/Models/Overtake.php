<?php

namespace FormulaTG\Models;

use FormulaTG\Utils\Helper;

class Overtake extends BaseModel
{
    private int $carOvertaking;
    private int $carOvertaken;
    private int $raceId;

    public function __construct(
        int $carOvertaking,
        int $carOvertaken, 
        int $raceId, 
        int $id = 0
    ) {
        $this->id = $id;
        $this->carOvertaking =  $carOvertaking;
        $this->carOvertaken =  $carOvertaken;
        $this->raceId =  $raceId;
    }

    public static function getTableColumns(): array
    {
        return [
            'car_overtaking',
            'car_overtaken',
            'race_id',
        ];
    }

    public function getInsertValues(): array
    {
        return [
            'car_overtaking' => $this->carOvertaking,
            'car_overtaken' => $this->carOvertaken,
            'race_id' => $this->raceId,
        ];
    }

    public static function populate(array $data): Overtake
    {
        Helper::validatePopulate($data, self::getTableColumns(), 'Overtake');

        return new Overtake(
            $data['car_overtaking'],
            $data['car_overtaken'],
            $data['race_id'],
            $data['id']
        );
    }

    public function stringfy(): string
    {
        return "Car nº $this->carOvertaking overtook car nº $this->carOvertaken";
    }
}
