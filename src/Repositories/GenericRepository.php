<?php

namespace FormulaTG\Repositories;

use Exception;
use FormulaTG\Models\BaseModel;
use FormulaTG\Models\Race;
use PDO;

class GenericRepository
{
    protected PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    private function createInsertQuery(string $table, array $columns): string
    {
        $values = [];
        foreach($columns as $column) {
            $values[] = ':' . $column;
        }

        $insertColumns = implode(', ', $columns);
        $valueColumns = implode(', ', $values);
        
        return "INSERT INTO ${table} (${insertColumns}) VALUES ($valueColumns)";
    }

    private function createExecuteOptions(array $columns, array $values): array
    {
        $executeOption = [];

        foreach ($columns as $column) {
            $executeOption[':' . $column] = $values[$column];
        }
        
        return $executeOption;
    }

    public function insert(BaseModel $entity, string $table, array $columns): bool
    {
        $stmt = $this->conn->prepare($this->createInsertQuery($table, $columns));

        try {
            $success = $stmt->execute($this->createExecuteOptions($columns, $entity->getInsertValues()));
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        if ($success) {
            $entity->defineId($this->conn->lastInsertId());
        }

        return $success;
    }

    /**
     * @return array|bool
     */
    public function getById(string $table, array $selectColumns, $id)
    {
        $select = implode(', ', $selectColumns);
        $stmt = $this->conn->prepare("SELECT ${select} FROM ${table} WHERE id = ${id};");

        try {
            if (!$stmt || !$stmt->execute()) {
                return [];
            }
            
            $entityData = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $entityData;
    }

    public function listAll(string $table): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM ${table}");
        
        try {
            if (!$stmt || !$stmt->execute()) {
                return [];
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function get(string $table, array $select, array $where): array
    {
        $selectQuery = implode(',', $select);
        $whereQuery = '';
        foreach ($where as $field => $operatorAndValue) {
            $whereQuery .= "$field $operatorAndValue[0] $operatorAndValue[1] ";
        }

        $stmt = $this->conn->prepare("SELECT $selectQuery FROM $table WHERE $whereQuery");

        try {
            if (!$stmt || !$stmt->execute()) {
                return [];
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
            return [];
        }
    }
}