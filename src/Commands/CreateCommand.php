<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Commands\Command;
use FormulaTG\Exceptions\CommandException;
use FormulaTG\Models\Car;
use FormulaTG\Models\Race;
use FormulaTG\Validators\Command\Create\CreateCommandValidation;
use FormulaTG\Validators\Logic\Create\ValidateCarCreationLogic;
use FormulaTG\Validators\Logic\Create\ValidateRaceCreationLogic;

class CreateCommand extends Command
{
    private string $entity;
    private array $formedParams;

    protected function identifyEntity(): void
    {
        // TODO percorrer procurando uma das entidades do array
        $this->entity = $this->params[0];
    }

    protected function validate(): void
    {
        $this->identifyEntity();

        $commandValidator = null;
        $logicValidator = null;

        switch ($this->entity) {
            case 'car':
                $commandValidator = new CreateCommandValidation(
                    [
                        'color', 
                        'equip', 
                        'driverName',
                    ],
                    'car',
                );
                $logicValidator = new ValidateCarCreationLogic();
                break;
            
            case 'race':
                $commandValidator = new CreateCommandValidation(['name'], 'race');
                $logicValidator = new ValidateRaceCreationLogic();
                break;

            default:
                throw new CommandException('Entity not valid to be created!');
                break;
        }

        $formedParams = $commandValidator->validate($this->params);
        $logicValidator->validate($formedParams);

        $this->formedParams = $formedParams;
    }

    private function createEntity(): string
    {
        switch ($this->entity) {
            case 'car':
                $car = new Car(
                    $this->formedParams['color'], 
                    $this->formedParams['equip'], 
                    $this->formedParams['driverName'],
                );
                $this->repository->insert($car, 'car', Car::getTableColumns());
                
                return $car->stringfy();

            case 'race':
                $race = new Race($this->formedParams['name'], null, null);
                $this->repository->insert($race, 'race', Race::getTableColumns());
                
                return $race->stringfy();
                break;

            default:
                return 'Unexpected entity';
        }
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $this->createEntity();
    }
}
