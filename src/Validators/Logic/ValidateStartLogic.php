<?php

namespace FormulaTG\Validators\Logic;

use FormulaTG\Config\Database\Connection;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Repositories\GenericRepository;
use FormulaTG\Validators\Logic\ValidateLogic;
use LogicException;

class ValidateStartLogic implements ValidateLogic
{
    private GenericRepository $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(Connection::createConnection());
    }

    public function validate(array $params): void
    {
        $races = $this->repository->listAll('race');

        $this->checkRaceExistenceAndStatus($params[0], $races);
        $this->checkForStartedRaces($races);
        $this->checkForCompetitors($params[0]);
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

    private function checkForCompetitors(int $raceId): void
    {
        $where = ['race_id' => ['=', $raceId]];
        $raceCompetitors = $this->repository->get('competitor', ['*'], $where);

        if (empty($raceCompetitors)) {
            throw new LogicException('The race has no competitors');
        }
    }
}