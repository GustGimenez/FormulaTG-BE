<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Config\Database\Connection;
use FormulaTG\Models\Overtake;
use FormulaTG\Models\Race;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Repositories\CompetitorRepository;
use FormulaTG\Validators\Command\OvertakeCommandValidation;
use FormulaTG\Validators\Logic\ValidateOvertakeLogic;

class OvertakeCommand extends Command
{
    protected function validate(): void
    {
        $commandValidator = new OvertakeCommandValidation();
        $this->params = $commandValidator->validate($this->params);

        $logicValidator = new ValidateOvertakeLogic();
        $logicValidator->validate($this->params);
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $competitorRepository = new CompetitorRepository(Connection::createConnection());
        $race = $this->getOnGoingRace();
        $competitors = $competitorRepository->getCompetitorsInRace(
            $race->getId(),
            [
                $this->params['overtaking'],
                $this->params['overtaken'],
            ]
        );
        
        $overtake = $this->createOvertake($race);
        $this->updateCompetitorsPosition($competitors, $competitorRepository);

        return $overtake->stringfy();
    }

    private function createOvertake(Race $race): Overtake
    {
        $overtake = new Overtake(
            $this->params['overtaking'],
            $this->params['overtaken'],
            $race->getId()
        );
        $this->repository->insert($overtake, 'overtake', Overtake::getTableColumns());

        return $overtake;
    }

    // TODO mover para RaceRepository
    private function getOnGoingRace(): Race
    {
        $where = ['status_id' => ['=', RaceStatus::STARTED]];
        $raceStarted = $this->repository->get('race', ['*'], $where);

        return Race::populate($raceStarted[0]);
    }

    /**
     * @param Competitor[] $competitors
     */
    private function updateCompetitorsPosition(
        array $competitors,
        CompetitorRepository $competitorRepository
    ): void {
        if ($competitors[0]->getCarId() === intval($this->params['overtaking'])) {
            $competitorRepository->updatePosition($competitors[0]->getId(), $competitors[1]->getPosition());
            $competitorRepository->updatePosition($competitors[1]->getId(), $competitors[0]->getPosition());
        } else {
            $competitorRepository->updatePosition($competitors[1]->getId(), $competitors[0]->getPosition());
            $competitorRepository->updatePosition($competitors[0]->getId(), $competitors[1]->getPosition());
        }
    }
}