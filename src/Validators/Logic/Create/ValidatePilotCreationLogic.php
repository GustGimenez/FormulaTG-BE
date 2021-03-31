<?php

namespace FormulaTG\Validators\Logic\Create;

use FormulaTG\Config\Database\Connection;
use FormulaTG\Exceptions\CommandException;
use FormulaTG\Repositories\GenericRepository;
use FormulaTG\Validators\Logic\ValidateLogic;

class ValidatePilotCreationLogic implements ValidateLogic
{
    private GenericRepository $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(Connection::createConnection());
    }

    public function validate(array $params): void
    {
        $this->checkIfPilotAlreadyExists($params['name']);
        $this->checkPilotAge($params['age']);
    }

    private function checkIfPilotAlreadyExists(string $name): void
    {
        $where = ['name' => ['=', "'$name'"]];
        $pilots = $this->repository->get('pilot', ['*'], $where);

        if (!empty($pilots)) {
            throw new CommandException("A pilot with the name $name alread exists");
        }
    }

    private function checkPilotAge(int $age): void
    {
        if ($age < 18) {
            throw new CommandException('Only pilots above 18 years old are allowed to race');
        }
    }
}
