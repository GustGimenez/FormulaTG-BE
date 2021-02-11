<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Config\Database\Connection;
use FormulaTG\Models\Race;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Repositories\RaceRepository;
use FormulaTG\Validators\Logic\ValidateFinishLogic;

class FinishCommand extends Command
{
    protected function validate(): void
    {
        $logicValidator = new ValidateFinishLogic();
        $logicValidator->validate($this->params);
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $overviewCommand = new OverviewCommand([]);
        $overviewOutput = $overviewCommand->execute();
        $race = $this->finishRace();

        return "{$race->getName()} finished" . PHP_EOL . $overviewOutput;
    }

    private function finishRace(): Race
    {
        $raceRepository = new RaceRepository(Connection::createConnection());
        $race = $this->getOnGoingRace();
        
        $raceRepository->changeStatus($race->getId(), RaceStatus::FINESHED);

        return $race;
    }

    private function getOnGoingRace(): Race
    {
        $where = ['status_id' => ['=', RaceStatus::STARTED]];
        $raceStarted = $this->repository->get('race', ['*'], $where);

        return Race::populate($raceStarted[0]);
    }
}
