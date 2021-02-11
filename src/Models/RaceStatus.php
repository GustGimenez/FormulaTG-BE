<?php

namespace FormulaTG\Models;

class RaceStatus
{
    private int $id;
    private string $name;

    public const NOT_STARTED = 1;
    public const STARTED = 2;
    public const FINESHED = 3;

    public function __construct(string $name, int $id = 0)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function getTableColumns(): array
    {
        return [];
    }

    public function getInsertValues(): array
    {
        return [];
    }

    public static function getStatusNameById(int $id): string
    {
        switch ($id) {
            case 1:
                return 'Not started';

            case 2:
                return 'Started';

            case 3:
                return 'Finished';
        }
    }
}