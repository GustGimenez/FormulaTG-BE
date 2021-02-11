<?php 

namespace FormulaTG\Models;

use Exception;

class Competitor extends BaseModel
{
    private int $position;
    private int $raceId;
    private int $carId;

    public function __construct(int $position, int $raceId, int $carId, int $id = 0)
    {
        $this->id = $id;
        $this->position = $position;
        $this->raceId = $raceId;
        $this->carId = $carId;
    }

    public static function getTableColumns(): array
    {
        return [
            'position',
            'race_id',
            'car_id',
        ];
    }

    public function getInsertValues(): array
    {
        return [
            'position' => $this->position,
            'race_id' => $this->raceId,
            'car_id' => $this->carId,
        ];
    }

    public static function populate(array $data): BaseModel
    {
        self::validatePopulate($data);

        return new Competitor(
            $data['position'],
            $data['race_id'],
            $data['car_id'],
            $data['id']
        );
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

    public function stringfy(): string
    {
        return "Competitor car nº: {$this->carId}, position: {$this->position}";
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getCarId(): int
    {
        return $this->carId;
    }
}