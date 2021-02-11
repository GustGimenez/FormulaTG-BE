<?php 

namespace FormulaTG\Models;

use Exception;

abstract class BaseModel
{
    protected int $id;

    public function defineId(int $id): void 
    {
        if ($this->id !== 0) {
            throw new Exception('Id has already been set');
        }

        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static abstract function getTableColumns(): array;

    public abstract function getInsertValues(): array;

    public static abstract function populate(array $data): BaseModel;

    protected static abstract function validatePopulate(array $data): void;

    public abstract function stringfy(): string;
}