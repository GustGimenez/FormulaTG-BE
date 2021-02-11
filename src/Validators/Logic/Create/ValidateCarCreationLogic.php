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
        $cars = $this->repository->listAll('car');
        
        $this->checkDriverName($cars, $params['driverName']);
        $this->checkEquipCount($cars, $params['equip']);
    }

    private function checkDriverName(array $cars, string $driverName): void
    {
        foreach ($cars as $car) {
            if ($car['driver_name'] === $driverName) {
                throw new CommandException("The driver informed is the pilot of car nº {$car['id']}");
            }
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