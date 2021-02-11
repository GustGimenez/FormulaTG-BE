<?php

namespace FormulaTG\Validators\Logic;

use FormulaTG\Config\Database\Connection;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Repositories\GenericRepository;
use LogicException;

class ValidateOverviewLogic implements ValidateLogic
{
    private GenericRepository $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(Connection::createConnection());
    }

    public function validate(array $params): void
    {
        $where = ['status_id' => ['=', RaceStatus::STARTED]];
        $raceStarted = $this->repository->get('race', ['*'], $where);

        if (empty($raceStarted)) {
            throw new LogicException('There is not started race!');
        }
    }
}
