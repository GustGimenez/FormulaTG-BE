<?php

namespace FormulaTG\Models;

use Exception;

class Race extends BaseModel
{
    private string $name;
    private RaceStatus $status;
    // private Competitor[] $competitors = [];

    public function __construct(string $name, ?RaceStatus $status, ?int $id)
    {
        $this->id = $id ?? 0;
        $this->name = $name;
        $this->status = $status ?? new RaceStatus('Not started', 1);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): RaceStatus
    {
        return $this->status;
    }

    public static function getTableColumns(): array
    {
        return [
            'name',
            'status_id',
        ];
    }

    public function getInsertValues(): array
    {
        return [
            'name' => $this->name,
            'status_id' => $this->status->getId(),
        ];
    }

    public static function populate(array $data): BaseModel
    {
        self::validatePopulate($data);

        return new Race(
            $data['name'],
            new RaceStatus(RaceStatus::getStatusNameById($data['status_id']), $data['status_id']), 
            $data['id'],
        );
    }

    public static function validatePopulate(array $data): void 
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
        return "Race nº: {$this->id}, name: {$this->name}, status: {$this->status->getName()}";
    }
}
