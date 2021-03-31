<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Commands\Command;
use FormulaTG\Exceptions\CommandException;
use FormulaTG\Models\Car;
use FormulaTG\Models\Pilot;
use FormulaTG\Models\Race;
use FormulaTG\Utils\Helper;
use FormulaTG\Validators\Command\CountParams;
use FormulaTG\Validators\Command\ParamsValues;
use FormulaTG\Validators\Command\ParamsWereInformed;
use FormulaTG\Validators\Logic\Create\ValidateCarCreationLogic;
use FormulaTG\Validators\Logic\Create\ValidatePilotCreationLogic;
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

        $this->params = Helper::removeEntityFromParams($this->params);
        $this->formedParams = Helper::formParams(array_values($this->params));

        $logicValidator = null;
        $expectedParams = [];

        switch ($this->entity) {
            case 'car':
                $expectedParams = [
                    'color', 
                    'equip', 
                    'pilot',
                ];

                $logicValidator = new ValidateCarCreationLogic();
                break;
            
            case 'race':
                $expectedParams = ['name'];
                
                $logicValidator = new ValidateRaceCreationLogic();
                break;

            case 'pilot':
                $expectedParams = ['name', 'age'];

                $logicValidator = new ValidatePilotCreationLogic();
                break;

            default:
                throw new CommandException('Entity not valid to be created!');
                break;
        }

        $validateParamsQuantity = new CountParams('create', $expectedParams);
        $validateIfInformed = new ParamsWereInformed('create', $expectedParams);
        $validateParamsValues = new ParamsValues('create', $expectedParams);

        $validateIfInformed->setNext($validateParamsValues);
        $validateParamsQuantity->setNext($validateIfInformed);

        $validateParamsQuantity->validate($this->formedParams);
        $logicValidator->validate($this->formedParams);
    }

    private function createEntity(): string
    {
        switch ($this->entity) {
            case 'car':
                $car = new Car(
                    $this->formedParams['color'], 
                    $this->formedParams['equip'], 
                    $this->formedParams['pilot'],
                );
                $this->repository->insert($car, 'car', Car::getTableColumns());
                
                return $car->stringfy();

            case 'race':
                $race = new Race($this->formedParams['name'], null, null);
                $this->repository->insert($race, 'race', Race::getTableColumns());
                
                return $race->stringfy();

            case 'pilot':
                $pilot = new Pilot(
                    $this->formedParams['name'],
                    $this->formedParams['age'],
                );
                $this->repository->insert($pilot, 'pilot', Pilot::getTableColumns());

                return $pilot->stringfy();
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
