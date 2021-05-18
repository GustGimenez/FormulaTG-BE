<?php

namespace FormulaTG\Commands;

use Exception;
use FormulaTG\Models\Competitor;
use FormulaTG\Models\Race;
use FormulaTG\Models\RaceStatus;
use FormulaTG\Validators\Logic\ValidateOverviewLogic;

class OverviewCommand extends Command
{
    protected function validate(): void
    {
        $logicValidator = new ValidateOverviewLogic();
        $logicValidator->validate($this->params);
    }

    public function execute(): string
    {
        try {
            $this->validate();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $competitors = $this->getRaceCompetitors();
        $overview = '';

        foreach ($competitors as $competitor) {
            $overview .= $competitor->stringfy() . PHP_EOL;
        }

        return $overview;
    }

    private function getRaceCompetitors(): array
    {
        $race = $this->getOnGoingRace();
        
        $where = ['race_id' => ['=', $race->getId()]];
        $competitors = $this->repository->get('competitor', ['*'], $where);

        $competitors = array_map(function($competitor) {
            return Competitor::populate($competitor);
        }, $competitors);

        usort($competitors, function($competitor1, $competitor2) {
            return $competitor1->getPosition() < $competitor2->getPosition() ? 0 : 1;
        });

        return $competitors;
    }

    private function getOnGoingRace(): Race
    {
        $where = ['status_id' => ['=', RaceStatus::STARTED]];
        $raceStarted = $this->repository->get('race', ['*'], $where);

        return Race::populate($raceStarted[0]);
    }
}
