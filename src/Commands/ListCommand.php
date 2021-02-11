<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Exceptions\CommandException;
use FormulaTG\Models\Car;
use FormulaTG\Models\Race;
use FormulaTG\Repositories\GenericRepository;

class ListCommand extends Command
{
    private string $entity;
    private array $entities = ['car', 'competitor', 'race'];

    protected function validate(): void
    {
        if (count($this->params) === 0) {
            throw new CommandException('Inform the entity to be listed');
        }

        if (count($this->params) > 1) {
            throw new CommandException('The list command expects only the entity');
        }
        
        if (!in_array($this->params[0], $this->entities)) {
            throw new CommandException('The entity informed is not supported by the --list command');
        }
    }

    protected function identifyEntity(): void
    {
        $this->entity = $this->params[0];
    }

    private function stringfyEntities(array $entities): string
    {
        $outputString = '';
        foreach ($entities as $entity) {
            $outputString .= $entity->stringfy() . PHP_EOL;
        }

        return $outputString;
    }

    private function listEntities(string $tableName, string $modelName): string
    {
        $dbData = $this->repository->listAll($tableName);
        if (empty($dbData)) {
            return "There is no $tableName registered!";
        }

        $entities = [];
        foreach ($dbData as $data) {
            try {
                $entities[] = $modelName::populate($data);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        return $this->stringfyEntities($entities);
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (CommandException $ce) {
            return $ce->getMessage();
        }

        $this->identifyEntity();

        switch ($this->entity) {
            case 'car':
                return $this->listEntities('car', Car::class);

            case 'race':
                return $this->listEntities('race', Race::class);
        }
    }
}
