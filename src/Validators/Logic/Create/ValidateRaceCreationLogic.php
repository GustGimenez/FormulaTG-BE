<?php 

namespace FormulaTG\Validators\Logic\Create;

use FormulaTG\Config\Database\Connection;
use FormulaTG\Exceptions\CommandException;
use FormulaTG\Repositories\GenericRepository;
use FormulaTG\Validators\Logic\ValidateLogic;

class ValidateRaceCreationLogic implements ValidateLogic
{
    private GenericRepository $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(Connection::createConnection());
    }

    public function validate(array $params): void
    {
        $races = $this->repository->listAll('race');

        $this->validateRaceName($races, $params['name']);
    }

    private function validateRaceName(array $races, string $raceName): void
    {
        foreach ($races as $race) {
            if ($race['name'] === $raceName) {
                throw new CommandException("The name \"$raceName\" is already in use");
            }
        }
    }
}