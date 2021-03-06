<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Exceptions\CommandException;
use FormulaTG\Models\Car;
use FormulaTG\Models\Pilot;
use FormulaTG\Models\Race;
use FormulaTG\Validators\Command\CountParams;

class ListCommand extends Command
{
    private string $entity;

    protected function validate(): void
    {
        $validateParamsQuantity = new CountParams('list', ['entity']);
        $validateParamsQuantity->validate($this->params);
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

            case 'pilot':
                return $this->listEntities('pilot', Pilot::class);

            default:
                return 'Invalid entity to list';
        }
    }
}
