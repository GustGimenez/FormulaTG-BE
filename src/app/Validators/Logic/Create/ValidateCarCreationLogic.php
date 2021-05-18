<?php

namespace FormulaTG\Validators\Logic\Create;

use FormulaTG\Config\Database\Connection;
use FormulaTG\Exceptions\CommandException;
use FormulaTG\Repositories\GenericRepository;
use FormulaTG\Validators\Logic\ValidateLogic;

class ValidateCarCreationLogic implements ValidateLogic
{
    private GenericRepository $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(Connection::createConnection());
    }

    public function validate(array $params): void
    {
        $this->checkIfPilotExists($params['pilot']);

        $cars = $this->repository->listAll('car');

        $this->checkIfPilotIsInAnotherCar($params['pilot'], $cars);
        $this->checkEquipCount($cars, $params['equip']);
    }

    private function checkIfPilotExists(int $pilotId): void
    {
        $where = ['id' => ['=', $pilotId]];

        $pilot = $this->repository->get('pilot', ['*'], $where);

        if (empty($pilot)) {
            throw new CommandException("Pilot $pilotId does not exist!");
        }
    }

    private function checkIfPilotIsInAnotherCar(int $pilotId, array $cars): void
    {
        $carWithPilot = array_filter($cars, function($car) use ($pilotId) {
            return intval($car['pilot_id']) === $pilotId;
        });

        if (!empty($carWithPilot)) {
            throw new CommandException("The pilot $pilotId is already in another car");
        }
    }

    private function checkEquipCount(array $cars, string $equip): void
    {
        $carsOnTheEquip = array_filter($cars, function($car) use ($equip) {
            return $car['equip'] === $equip;
        });

        if (count($carsOnTheEquip) === 2) {
            throw new CommandException("There are already two cars representing $equip");
        }
    }
}