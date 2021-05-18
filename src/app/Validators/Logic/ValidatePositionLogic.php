<?php

namespace FormulaTG\Validators\Logic;

use FormulaTG\Models\RaceStatus;
use FormulaTG\Validators\Logic\ValidateLogic;
use LogicException;

class ValidatePositionLogic extends BaseValidateLogic implements ValidateLogic
{
    public function validate(array $params): void
    {
        $carsIds = array_unique(explode(',', $params['cars']));
        
        $races = $this->repository->listAll('race');

        $this->checkCarsQuantity($carsIds);
        $this->checkIfCarsExists($carsIds);
        $this->checkRaceExistenceAndStatus($params['race'], $races);
        $this->checkForStartedRaces($races);
        $this->checkIfCarsAreAlreadyCompeting($carsIds, $params['race']);
    }

    private function checkCarsQuantity(array $cars): void
    {
        if (count($cars) < 2) {
            throw new LogicException('There must be at least two competitors in a race');
        }
    }

    private function checkRaceExistenceAndStatus(int $raceId, array $races): void 
    {
        if (empty($races)) {
            throw new LogicException('There are no races in the database');
        }

        foreach ($races as $race) {
            if (intval($race['id']) === $raceId) {
                if (intval($race['status_id']) === RaceStatus::FINESHED ||
                    intval($race['status_id']) === RaceStatus::STARTED
                    ) {
                        throw new LogicException('This race cannot be started');
                    }

                return;
            }
        }

        throw new LogicException('There is no race with the identifier informed');
    }

    private function checkForStartedRaces(array $races): void
    {
        foreach ($races as $race) {
            if (intval($race['status_id']) === RaceStatus::STARTED) {
                throw new LogicException('There is a race on going');
            }
        }
    }

    private function checkIfCarsExists(array $carsIds): void
    {
        $cars = $this->repository->listAll('car');

        foreach ($carsIds as $id) {
            $exists = false;
            foreach ($cars as $car) {
                if (intval($car['id']) === intval($id)) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                throw new LogicException("Car $id does not exist");
            }
        }
    }

    private function checkIfCarsAreAlreadyCompeting(array $carsIds, int $raceId): void 
    {
        $where = ['race_id' => ['=', $raceId]];
        $competitors = $this->repository->get('competitor', ['*'], $where);

        foreach ($competitors as $competitor) {
            if (in_array($competitor['car_id'], $carsIds)) {
                throw new LogicException("The car {$competitor['car_id']} is already competing in the race");
            }
        }
    }
}